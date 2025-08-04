<?php

namespace App\Services;

use App\Models\product;
use App\Models\product_variants;
use App\Models\product_variant_options;
use App\Models\variant_attributes;
use App\Models\variant_options;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductService
{
    /**
     * Get all products with filters and pagination
     */
    public function getAllProducts(array $filters = [], int $perPage = 15)
    {
        $query = product::with(['category', 'brand', 'variants']);

        // Apply filters
        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['brand_id'])) {
            $query->where('brand_id', $filters['brand_id']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Apply sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortBy, $sortDirection);

        return $query->paginate($perPage);
    }

    /**
     * Create a new product
     */
    public function createProduct(array $data): product
    {
        return product::create($data);
    }

    /**
     * Get product by ID with relationships
     */
    public function getProductById(int $id): ?product
    {
        return product::with(['category', 'brand', 'variants.variantOptions.attribute', 'variants.variantOptions.option', 'images'])
                     ->find($id);
    }

    /**
     * Update product
     */
    public function updateProduct(int $id, array $data): bool
    {
        $product = product::find($id);
        if (!$product) {
            return false;
        }

        return $product->update($data);
    }

    /**
     * Delete product
     */
    public function deleteProduct(int $id): bool
    {
        $product = product::find($id);
        if (!$product) {
            return false;
        }

        return $product->delete();
    }

    /**
     * Create product variant with options
     */
    public function createProductVariant(int $productId, array $variantData, array $optionsData = []): product_variants
    {
        DB::beginTransaction();

        try {
            // Create variant
            $variant = product_variants::create([
                'product_id' => $productId,
                'sku' => $variantData['sku'] ?? null,
                'price' => $variantData['price'] ?? 0,
                'stock_quantity' => $variantData['stock_quantity'] ?? 0,
                'status' => $variantData['status'] ?? true
            ]);

            // Create variant options
            foreach ($optionsData as $attributeId => $attributeData) {
                if (empty($attributeData['attribute_id']) || empty($attributeData['options'])) {
                    continue;
                }

                $options = is_string($attributeData['options'])
                    ? json_decode($attributeData['options'], true)
                    : $attributeData['options'];

                if (!is_array($options)) {
                    continue;
                }

                foreach ($options as $optionValue) {
                    // Find or create option
                    $option = variant_options::firstOrCreate([
                        'attribute_id' => $attributeData['attribute_id'],
                        'value' => $optionValue
                    ]);

                    // Create variant option
                    product_variant_options::create([
                        'variant_id' => $variant->id,
                        'attribute_id' => $attributeData['attribute_id'],
                        'option_id' => $option->id
                    ]);
                }
            }

            DB::commit();
            return $variant;

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating product variant: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get variant attributes
     */
    public function getVariantAttributes()
    {
        return variant_attributes::with('options')->get();
    }

    /**
     * Get attribute options
     */
    public function getAttributeOptions(int $attributeId)
    {
        return variant_options::where('attribute_id', $attributeId)->get();
    }

    /**
     * Get product variants
     */
    public function getProductVariants(int $productId)
    {
        return product_variants::where('product_id', $productId)
                              ->with(['variantOptions.attribute', 'variantOptions.option'])
                              ->get();
    }

    /**
     * Update variant
     */
    public function updateVariant(int $variantId, array $data): bool
    {
        $variant = product_variants::find($variantId);
        if (!$variant) {
            return false;
        }

        return $variant->update($data);
    }

    /**
     * Delete variant
     */
    public function deleteVariant(int $variantId): bool
    {
        DB::beginTransaction();

        try {
            $variant = product_variants::find($variantId);
            if (!$variant) {
                return false;
            }

            // Delete variant options
            product_variant_options::where('variant_id', $variantId)->delete();

            // Delete variant
            $variant->delete();

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting variant: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Search products
     */
    public function searchProducts(string $query, array $filters = [], int $perPage = 10)
    {
        $productQuery = product::with(['category', 'brand'])
                              ->where('name', 'like', '%' . $query . '%');

        // Apply filters
        if (!empty($filters['category_id'])) {
            $productQuery->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['brand_id'])) {
            $productQuery->where('brand_id', $filters['brand_id']);
        }

        if (!empty($filters['price_min'])) {
            $productQuery->whereHas('variants', function($q) use ($filters) {
                $q->where('price', '>=', $filters['price_min']);
            });
        }

        if (!empty($filters['price_max'])) {
            $productQuery->whereHas('variants', function($q) use ($filters) {
                $q->where('price', '<=', $filters['price_max']);
            });
        }

        return $productQuery->paginate($perPage);
    }

    /**
     * Export products
     */
    public function exportProducts(array $filters, callable $callback)
    {
        $query = product::with(['category', 'brand'])
                       ->withCount('variants');

        // Apply filters
        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['brand_id'])) {
            $query->where('brand_id', $filters['brand_id']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Process in chunks to avoid memory issues
        $query->chunk(100, $callback);
    }
}
