<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\ActivateProductRequest;
use App\Http\Requests\Product\StoreProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function __construct(protected ProductService $productService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->productService->search($request, Product::class);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        if($request->validated()){
            $product = $this->productService->create($request, Product::class);
            return response()->json(['message' => 'Added successfully', 'product' => $product], 200);
        }
        else{
            $errors = $request->errors()->all();
            return response()->json(['errors' => $errors], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product = $this->productService->show($product->id, Product::class);
        // Product::with()
        //Log::info("test", [$product->productType]);
        return response()->json([
            'message' => $product ? 'Product found successfully' : 'Not found',
            'product' => $product ?? null
        ], $product ? 200 : 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $updatedProduct = $this->productService->partialUpdate($request, Product::class, $product->id);
        return response()->json(['message' => 'Updated successfully', 'product' => $updatedProduct], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $removed = $this->productService->destroy($product->id, Product::class);
        return response()->json([
            'message' => $removed ? 'deleted successfully' : 'not found'
        ], $removed ? 200 : 404);
    }

    public function activate(ActivateProductRequest $activateProductRequest, Product $product){
        $this->productService->activate($product, $activateProductRequest);
    }

    public function draft(Request $request, Product $product){
        $this->productService->draft($product);
    }

    public function delete(Request $request, Product $product){
        $this->productService->deleted($product);
    }
}
