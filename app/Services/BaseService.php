<?php

namespace App\Services;

use App\Models\ProductType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BaseService
{

    public function search(Request $request, $modelQuery)
    {
        $perPage = $request->input('per_page', 5);
        $page = $request->input('page', 1);

        return $modelQuery->paginate($perPage, ['*'], 'page', $page);
    }

    public function create(Request $request, $model)
    {
        $object = $model::create($request->all());
        return $object;
    }

    public function show(string $id, $model)
    {
        $object = $model::findOrFail($id);
        return $object;
    }

    public function partialUpdate(Request $request, $model, string $id)
    {
        $object = $model::find($id);

        if ($object) {
            $object->fill($request->all())->save();
        }
        return $object;
    }

    public function destroy(string $id, $model)
    {
        $object = $model::find($id);
        if ($object) {
            $object->delete();
            return ['statusCode' => 200, 'message' => 'Removed successfully'];
        }
        return ['statusCode' => 404, 'message' => 'Not found'];
    }
}
