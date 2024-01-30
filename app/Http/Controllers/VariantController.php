<?php

namespace App\Http\Controllers;

use App\Http\Requests\Variant\StoreVariantRequest;
use App\Models\Variant;
use App\Services\BaseService;
use App\Services\VariantService;
use Illuminate\Http\Request;

class VariantController extends Controller
{
    public function __construct(protected VariantService $variantService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->variantService->search($request, Variant::class);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVariantRequest $request)
    {
        if ($request->validated()) {
            $variant = $this->variantService->create($request, Variant::class);

            return response()->json(
                [
                    'message' => $variant ? 'Added successfully' : 'Cannot create variant, product not in DRAFT',
                    'variant' => $variant
                ],
                $variant ? 200 : 403
            );
        } else {
            $errors = $request->errors()->all();
            return response()->json(['errors' => $errors], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Variant $variant)
    {
        $variant = $this->variantService->show($variant->id, Variant::class);
        $variant->product;
        return response()->json([
            'message' => $variant ? 'Variant found successfully' : 'Not found',
            'variant' => $variant ?? null
        ], $variant ? 200 : 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Variant $variant)
    {
        $updatedVariant = $this->variantService->partialUpdate($request, Variant::class, $variant->id);
        return response()->json(['message' => 'Updated successfully', 'variant' => $updatedVariant], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Variant $variant)
    {
        $response = $this->variantService->destroy($variant->id, Variant::class);
        return response()->json([
            'message' => $response['message'],
        ], $response['statusCode']);
    }
}
