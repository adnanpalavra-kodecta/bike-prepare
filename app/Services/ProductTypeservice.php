<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ProductTypeService extends BaseService
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

    public function processQueryParams($queryList, $model){
        $modelQuery = $model::query();
        foreach ($queryList as $key => $value) {
            switch ($key) {
                case 'name':
                case 'description':
                    $modelQuery->where(function ($query) use ($key, $value) {
                        $query->where($key, 'ILIKE', "%$value%");
                    });
                    break;
            }
        }
        return $modelQuery;
    }
}
