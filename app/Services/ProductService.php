<?php

namespace App\Services;

use App\Exceptions\UserException;
use App\Http\Resources\ProductWithNewestVariantResource;
use App\Models\Enums\ProductState;
use App\Models\Product;
use App\Models\ProductStateMachine\States\BaseState;
use App\Models\SearchObject\BaseSearchObject;
use App\Models\SearchObject\ProductSearchObject;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductService extends BaseService
{
    public function search(Request $request, $model)
    {
        $searchObject = new ProductSearchObject($request->query());
        $modelQuery = $model::query();
        $modelQuery = $this->addFilters($searchObject, $modelQuery);
        return $searchObject->searchPaginate($modelQuery);
    }

    public function show(string $id, $model)
    {
        $product = Product::find($id);
        if (!$product) {
            throw new UserException("Product not found!", 404);
        }
        return ProductWithNewestVariantResource::make($product);
    }

    public function processQueryParams($queryList, $model)
    {
        $modelQuery = $model::query();
        foreach ($queryList as $key => $value) {
            switch ($key) {
                case 'name':
                case 'description':
                    $modelQuery->where(function ($query) use ($key, $value) {
                        $query->where($key, 'ILIKE', "%$value%");
                    });
                    break;
                case 'validFrom':
                    $modelQuery->where('validFrom', '>=', $value);
                    break;
                case 'validTo':
                    $modelQuery->where('validTo', '<=', $value);
                    break;
                case 'includeProductType':
                    $modelQuery->with('productType');
                    break;
            }
        }
        return $modelQuery;
    }

    public function addFilters(BaseSearchObject|ProductSearchObject $searchObject, $query)
    {
        if ($searchObject->validFrom) {
            $query = $query->where('validFrom', '>=', $searchObject->validFrom);
        }

        if ($searchObject->validTo) {
            $query = $query->where('validTo', '<=', $searchObject->validTo);
        }

        if ($searchObject->max_price) {
            $query =
                $query->with('variants', function ($variantQuery) use ($searchObject) {

                    $variantQuery->where('price', '<', $searchObject->max_price);
                })->whereHas('variants', function ($variantQuery) use ($searchObject) {
                    $variantQuery->where('price', '<', $searchObject->max_price);
                });
        }
        if ($searchObject->min_price) {
            $query =
                $query->with('variants', function ($variantQuery) use ($searchObject) {

                    $variantQuery->where('price', '>', $searchObject->min_price);
                })->whereHas('variants', function ($variantQuery) use ($searchObject) {
                    $variantQuery->where('price', '>', $searchObject->min_price);
                });
        }

        if ($searchObject->name) {
            $query = $query->where('name', 'ILIKE', "%$searchObject->name%");
        }

        return $query;
    }
    public function activate(Product $product, Request $request)
    {
        $productState = BaseState::getState($product->state);
        $productState->moveToState($product, ProductState::ACTIVE, $request->all());
        Log::info("proddd", [$product]);
    }

    public function draft(Product $product)
    {
        $productState = BaseState::getState($product->state);
        $productState->moveToState($product, ProductState::DRAFT);
    }

    public function deleted(Product $product)
    {
        $productState = BaseState::getState($product->state);
        $productState->moveToState($product, ProductState::DRAFT);
    }
}
