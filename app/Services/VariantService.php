<?php

namespace App\Services;

use App\Models\Enums\ProductState;
use App\Models\Product;
use App\Models\ProductStateMachine\States\BaseState;
use App\Models\Variant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VariantService extends BaseService
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
                case 'min_price':
                    $modelQuery->where('price', '>=', $value);
                    break;
                case 'max_price':
                    $modelQuery->where('price', '<=', $value);
                    break;
            }
        }
        return $modelQuery;
    }
    public function create(Request $request, $model)
    {
        $product = Product::find($request->product_id);
        if ($product->state !== ProductState::DRAFT) {
            return null;
        }
        parent::create($request, Variant::class);
    }

    public function destroy(string $id, $model)
    {
        $variant = Variant::find($id);
        if ($variant->product->state !== ProductState::DRAFT) {
            return ['statusCode' => 403, 'message' => 'Product not in DRAFT state'];
        }
        return parent::destroy($id, Variant::class);
    }
}
