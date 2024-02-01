<?php

namespace App\Observers;

use App\Models\ProductType;
use Illuminate\Support\Facades\Cache;

class ProductTypeObserver
{
    /**
     * Handle the ProductType "created" event.
     */
    public function created(ProductType $productType): void
    {
        $this->forgetProductTypeCaches();
    }

    /**
     * Handle the ProductType "updated" event.
     */
    public function updated(ProductType $productType): void
    {
        $this->forgetProductTypeCaches();
    }

    /**
     * Handle the ProductType "deleted" event.
     */
    public function deleted(ProductType $productType): void
    {
        $this->forgetProductTypeCaches();
    }

    /**
     * Handle the ProductType "restored" event.
     */
    public function restored(ProductType $productType): void
    {
        $this->forgetProductTypeCaches();
    }

    /**
     * Handle the ProductType "force deleted" event.
     */
    public function forceDeleted(ProductType $productType): void
    {
        $this->forgetProductTypeCaches();
    }

    private function forgetProductTypeCaches()
    {
        $keys = Cache::getStore()->getPrefix() . 'productType;*';

        $matchingKeys = Cache::getStore()->get($keys);

        foreach ($matchingKeys as $key => $value) {
            Cache::forget($key);
        }
    }
}
