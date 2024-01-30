<?php

namespace App\Models\ProductStateMachine\States;

use App\Models\Enums\ProductState;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class BaseState
{

    public function allowedStates()
    {
        return [];
    }

    public function moveToState(Product $product, ProductState $productState, $dataToUpdate = null)
    {
        Log::info("testing func", [$product, $productState, $dataToUpdate]);
        Log::info("user in move", [auth()->user()]);
        $product = Product::find($product->id);
        if (is_null($dataToUpdate)) {
            Log::info("in is null");
            $product->update([
                'state' => $productState,
            ]);
        } else {
            Log::info("is not in is null", [
                'state' => $productState,
                ...$dataToUpdate,
            ]);
            $product->update([
                'state' => $productState,
                ...$dataToUpdate,
                // 'activatedBy' => auth()->user()->id;
            ]);
        }
        Log::info("product to return ",[$product]);
        return $product;
    }

    public static function getState(string $stateName)
    {
        switch ($stateName) {
            case 'DRAFT':
                return new DraftState();
            case 'ACTIVE':
                return new ActiveState();
            case 'DELETED':
                return new DeletedState();
        }
    }
}
