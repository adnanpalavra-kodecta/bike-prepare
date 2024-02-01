<?php

namespace App\Services;

use App\Http\Resources\ProductWithNewestVariantResource;
use App\Models\Enums\ProductState;
use App\Models\Product;
use App\Models\ProductStateMachine\States\BaseState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductService extends BaseService
{
    public function search(Request $request, $model)
    {
        if (!class_exists($model) || !is_subclass_of($model, Model::class)) {
            // Check if the class exists and if it's a subclass of Model
            dd($model);
        }
        $queryList = $request->query();
        $modelQuery = $this->processQueryParams($queryList, $model);

        return parent::search($request, $modelQuery);
    }

    public function show(string $id, $model)
    {
        $product = Product::find($id);
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
