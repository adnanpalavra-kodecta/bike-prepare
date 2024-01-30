<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductType\StoreProductTypeRequest;
use App\Models\ProductType;
use App\Services\ProductTypeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductTypeController extends Controller
{
    public function __construct(protected ProductTypeService $productTypeService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->productTypeService->search($request, ProductType::class);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductTypeRequest $request)
    {
        if($request->validated()){
            $productType = $this->productTypeService->create($request, ProductType::class);
            Log::info("product type", [$productType]);
            return response()->json(['message' => 'Added successfully', 'productType' => $productType], 200);
        }
        else{
            $errors = $request->errors()->all();
            return response()->json(['errors' => $errors], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductType $productType)
    {
        $productType = $this->productTypeService->show($productType->id, ProductType::class);
        return response()->json([
            'message' => $productType ? 'ProductType found successfully' : 'Not found',
            'productType' => $productType ?? null
        ], $productType ? 200 : 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductType $productType)
    {
        $updatedProductType = $this->productTypeService->partialUpdate($request, ProductType::class, $productType->id);
        return response()->json(['message' => 'Updated successfully', 'productType' => $updatedProductType], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductType $productType)
    {
        $removed = $this->productTypeService->destroy($productType->id, ProductType::class);
        return response()->json([
            'message' => $removed ? 'deleted successfully' : 'not found'
        ], $removed ? 200 : 404);
    }
}
