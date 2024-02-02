<?php

namespace App\Models\SearchObject;

use DateTime;

class ProductSearchObject extends BaseSearchObject
{
    public ?string $name = null;
    public ?bool $includeProductType = false;
    public ?bool $includeVariants = false;
    public ?float $min_price = null;
    public ?float $max_price = null;
    public ?DateTime $validFrom = null;
    public ?DateTime $validTo = null;
    public function __construct(array $attributes)
    {
        parent::__construct($attributes);
        $this->fill($attributes);
    }

    public function fill(array $attributes)
    {
        parent::fill($attributes);
        $this->includeProductType = $attributes['includeProductType'] ?? false;
        $this->includeVariants = $attributes['includeVariants'] ?? false;
        $this->validFrom = isset($attributes['validFrom']) ? new DateTime($attributes['validFrom']) : null;
        $this->validTo = isset($attributes['validTo']) ? new DateTime($attributes['validTo']) : null;
        $this->min_price = $attributes['min_price'] ?? null;
        $this->max_price = $attributes['max_price'] ?? null;
        $this->name = $attributes['name'] ?? null;
    }
}
