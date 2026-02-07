<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;



class ProductController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            Log::info('Product creation started', [
                'user_id' => $request->user()->id
            ]);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'brands' => 'required|array|min:1',
                'brands.*.name' => 'required|string|max:255',
                'brands.*.detail' => 'required|string',
                'brands.*.price' => 'required|numeric|min:0',
                'brands.*.image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            // Create product
            $product = Product::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'user_id' => $request->user()->id,
            ]);

            // Create brands
            foreach ($validated['brands'] as $index => $brandData) {
                $imagePath = $request
                    ->file("brands.$index.image")
                    ->store('brands', 'public');

                $product->brands()->create([
                    'name' => $brandData['name'],
                    'detail' => $brandData['detail'],
                    'price' => $brandData['price'],
                    'image' => $imagePath,
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Product created successfully',
                'product_id' => $product->id
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Product creation failed', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to create product'
            ], 500);
        }
    }

    public function index(Request $request)
    {
        $perPage = $request->get('limit', 10);

        $products = Product::with('brands')
            ->where('user_id', $request->user()->id)
            ->paginate($perPage);

        return response()->json([
            'status' => true,
            'data' => $products
        ], 200);
    }

    public function pdf($id, Request $request)
    {
        $product = Product::with('brands')
            ->where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $pdf = Pdf::loadView('pdf.product', compact('product'));

        return $pdf->stream('product.pdf');
    }

    public function destroy($id, Request $request)
    {
        try {
            $product = Product::with('brands')
                ->where('id', $id)
                ->where('user_id', $request->user()->id)
                ->firstOrFail();

            foreach ($product->brands as $brand) {
                if ($brand->image && Storage::disk('public')->exists($brand->image)) {
                    Storage::disk('public')->delete($brand->image);
                }
            }

            $product->delete();

            return response()->json([
                'status' => true,
                'message' => 'Product deleted successfully'
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found or unauthorized'
            ], 404);

        } catch (\Exception $e) {
            \Log::error('Delete failed', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to delete product'
            ], 500);
        }
    }


}
