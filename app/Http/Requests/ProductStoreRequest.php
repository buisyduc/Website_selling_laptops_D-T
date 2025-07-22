<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use App\Models\variant_attributes;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;


class ProductStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

   public function rules(): array
{
    return [
          'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:products,slug',
        'description' => 'required|string|min:10',
        'brand_id' => 'required|exists:brands,id',
        'category_id' => 'required|exists:categories,id',
        'image' => 'nullable|image',
        'images.*' => 'nullable|image',
        'release_date' => 'nullable|date|after_or_equal:today',
        'status' => 'required|boolean',

        // Biến thể sản phẩm
        'variants' => 'nullable|array',
        'variants.*.price' => 'required|numeric|min:0',
        'variants.*.stock_quantity' => 'required|integer|min:0',
        'variants.*.compare_price' => 'nullable|numeric',
        'variants.*.cost_price' => 'nullable|numeric',
        'variants.*.weight' => 'nullable|numeric',
        'variants.*.length' => 'nullable|numeric',
        'variants.*.width' => 'nullable|numeric',
        'variants.*.height' => 'nullable|numeric',
        'variants.*.attributes' => 'nullable|array',
        'variants.*.attributes.*.attribute_id' => 'nullable|integer',
        'variants.*.attributes.*.options' => 'nullable|array',
        'variants.*.attributes.*.options.*' => 'nullable|string|max:255',
    ];
}


    public function messages()
    {
        return [
            // Thông tin cơ bản
            'name.required' => 'Tên sản phẩm là bắt buộc.',
            'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự.',
            'slug.required' => 'Slug sản phẩm là bắt buộc.',
            'slug.unique' => 'Slug này đã được sử dụng.',
            'description.required' => 'Mô tả sản phẩm là bắt buộc.',
            'description.min' => 'Mô tả sản phẩm phải có ít nhất 10 ký tự.',
            'brand_id.required' => 'Vui lòng chọn thương hiệu.',
            'brand_id.exists' => 'Thương hiệu được chọn không hợp lệ.',
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.exists' => 'Danh mục được chọn không hợp lệ.',

            // Hình ảnh
            'image.image' => 'File tải lên phải là hình ảnh.',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp.',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
            'images.max' => 'Chỉ được tải lên tối đa 10 hình ảnh.',
            'images.*.image' => 'Tất cả file tải lên phải là hình ảnh.',
            'images.*.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp.',
            'images.*.max' => 'Kích thước mỗi hình ảnh không được vượt quá 2MB.',

            // Variants
            'variants.*.sku.unique' => 'SKU này đã tồn tại.',
            'variants.*.price.required' => 'Giá biến thể là bắt buộc.',
            'variants.*.price.numeric' => 'Giá phải là số.',
            'variants.*.price.min' => 'Giá không được âm.',
            'variants.*.stock_quantity.required' => 'Số lượng tồn kho là bắt buộc.',
            'variants.*.stock_quantity.integer' => 'Số lượng phải là số nguyên.',
            'variants.*.stock_quantity.min' => 'Số lượng không được âm.',

            // Attributes
            'variants.*.attributes.*.attribute_id.required' => 'Vui lòng chọn thuộc tính.',
            'variants.*.attributes.*.attribute_id.exists' => 'Thuộc tính không hợp lệ.',
            'variants.*.attributes.*.options.required' => 'Vui lòng thêm ít nhất một giá trị thuộc tính.',
        ];
    }

    public function prepareForValidation()
    {
        // Tự động tạo slug từ name nếu không có
        if (!$this->slug && $this->name) {
            $this->merge([
                'slug' => Str::slug($this->name)
            ]);
        }

        // Chuẩn hóa dữ liệu variants
        $this->normalizeVariantsData();
    }

    /**
     * Chuẩn hóa dữ liệu variants trước khi validation
     */
    // private function normalizeVariantsData()
    // {
    //     if (!$this->has('variants') || !is_array($this->variants)) {
    //         return;
    //     }

    //     $normalizedVariants = [];

    //     foreach ($this->variants as $key => $variant) {
    //         if (!is_array($variant)) {
    //             continue;
    //         }

    //         $normalizedVariant = $variant;

    //         // Chuẩn hóa attributes
    //         if (isset($variant['attributes']) && is_array($variant['attributes'])) {
    //             $normalizedAttributes = [];

    //             foreach ($variant['attributes'] as $attrKey => $attribute) {
    //                 if (!is_array($attribute)) {
    //                     continue;
    //                 }

    //                 $normalizedAttribute = $attribute;

    //                 // Chuẩn hóa options: chuyển đổi từ JSON string thành array nếu cần
    //                 if (isset($attribute['options'])) {
    //                     $normalizedAttribute['options'] = $this->normalizeOptions($attribute['options']);
    //                 }

    //                 // Nếu attribute_id không tồn tại, bỏ qua attribute này
    //                 if (empty($attribute['attribute_id'])) {
    //                     continue;
    //                 }

    //                 $normalizedAttributes[$attrKey] = $normalizedAttribute;
    //             }

    //             $normalizedVariant['attributes'] = $normalizedAttributes;
    //         }

    //         // Chỉ thêm variant nếu có giá và số lượng hợp lệ
    //         if (!empty($variant['price']) && !empty($variant['stock_quantity'])) {
    //             $normalizedVariants[$key] = $normalizedVariant;
    //         }
    //     }

    //     $this->merge(['variants' => $normalizedVariants]);
    // }

private function cleanNumber($value)
{
    if (is_null($value)) {
        return null;
    }

    // Xóa dấu . (ngăn cách hàng nghìn) và ép kiểu float
    return (int) str_replace('.', '', $value);
}

    private function normalizeVariantsData()
{
    if (!$this->has('variants') || !is_array($this->variants)) {
        return;
    }

    $normalizedVariants = [];

    foreach ($this->variants as $key => $variant) {
        if (!is_array($variant)) {
            continue;
        }

        $normalizedVariant = $variant;

        // Làm sạch dữ liệu số (giá, trọng lượng, kích thước...)
        $normalizedVariant['price'] = $this->cleanNumber($variant['price'] ?? null);
        $normalizedVariant['compare_price'] = $this->cleanNumber($variant['compare_price'] ?? null);
        $normalizedVariant['cost_price'] = $this->cleanNumber($variant['cost_price'] ?? null);
        $normalizedVariant['weight'] = $this->cleanNumber($variant['weight'] ?? null);
        $normalizedVariant['length'] = $this->cleanNumber($variant['length'] ?? null);
        $normalizedVariant['width'] = $this->cleanNumber($variant['width'] ?? null);
        $normalizedVariant['height'] = $this->cleanNumber($variant['height'] ?? null);
        $normalizedVariant['stock_quantity'] = isset($variant['stock_quantity']) ? (int) $variant['stock_quantity'] : 0;

        // Chuẩn hóa attributes
        if (isset($variant['attributes']) && is_array($variant['attributes'])) {
            $normalizedAttributes = [];

            foreach ($variant['attributes'] as $attrKey => $attribute) {
                if (!is_array($attribute)) {
                    continue;
                }

                $normalizedAttribute = $attribute;

                if (isset($attribute['options'])) {
                    $normalizedAttribute['options'] = $this->normalizeOptions($attribute['options']);
                }

                if (empty($attribute['attribute_id'])) {
                    continue;
                }

                $normalizedAttributes[$attrKey] = $normalizedAttribute;
            }

            $normalizedVariant['attributes'] = $normalizedAttributes;
        }

        // Chỉ thêm nếu có giá > 0 và số lượng >= 0
        if (!is_null($normalizedVariant['price']) && $normalizedVariant['price'] > 0
            && $normalizedVariant['stock_quantity'] >= 0) {
            $normalizedVariants[$key] = $normalizedVariant;
        }
    }

    $this->merge(['variants' => $normalizedVariants]);
}


    /**
     * Chuẩn hóa options thành array
     */
    protected function normalizeOptions($options)
{
    if (is_string($options)) {
        return array_map('trim', explode(',', $options)); // 'Đỏ, Xanh' → ['Đỏ', 'Xanh']
    }

    if (is_array($options)) {
        return array_filter(array_map('trim', $options)); // Làm sạch mảng
    }

    return [];
}


    /**
     * Custom validation cho options
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $this->validateVariantData($validator);
            $this->validateVariantOptions($validator);
            $this->validateVariantUniqueness($validator);
        });
    }

    /**
     * Validate basic variant data
     */
    private function validateVariantData($validator)
    {
        if (!$this->has('variants')) {
            return;
        }

        foreach ($this->variants as $variantKey => $variant) {
            // Kiểm tra nếu variant có attributes nhưng không có attribute_id
            if (isset($variant['attributes'])) {
                foreach ($variant['attributes'] as $attrKey => $attribute) {
                    if (empty($attribute['attribute_id'])) {
                        $validator->errors()->add(
                            "variants.{$variantKey}.attributes.{$attrKey}.attribute_id",
                            'Vui lòng chọn thuộc tính.'
                        );
                    }
                }
            }
        }
    }

    /**
     * Validate variant options
     */
    private function validateVariantOptions($validator)
    {
        if (!$this->has('variants')) {
            return;
        }

        foreach ($this->variants as $variantKey => $variant) {
            if (!isset($variant['attributes'])) {
                continue;
            }

            foreach ($variant['attributes'] as $attrKey => $attribute) {
                if (!isset($attribute['options'])) {
                    continue;
                }

                $options = $this->normalizeOptions($attribute['options']);

                // Kiểm tra có ít nhất 1 option
                if (empty($options)) {
                    $validator->errors()->add(
                        "variants.{$variantKey}.attributes.{$attrKey}.options",
                        'Phải có ít nhất một giá trị thuộc tính.'
                    );
                }

                // Kiểm tra độ dài mỗi option
                foreach ($options as $index => $option) {
                    if (strlen($option) > 100) {
                        $validator->errors()->add(
                            "variants.{$variantKey}.attributes.{$attrKey}.options.{$index}",
                            'Giá trị thuộc tính không được vượt quá 100 ký tự.'
                        );
                    }
                }
            }
        }
    }

    /**
     * Validate variant uniqueness (optional business rule)
     */
    private function validateVariantUniqueness($validator)
    {
        if (!$this->has('variants')) {
            return;
        }

        $seenCombinations = [];

        foreach ($this->variants as $variantKey => $variant) {
            if (!isset($variant['attributes'])) {
                continue;
            }

            // Tạo signature cho variant dựa trên attributes
            $signature = $this->createVariantSignature($variant['attributes']);

            if (in_array($signature, $seenCombinations)) {
                $validator->errors()->add(
                    "variants.{$variantKey}",
                    'Tổ hợp thuộc tính này đã tồn tại trong biến thể khác.'
                );
            }

            $seenCombinations[] = $signature;
        }
    }

    /**
     * Tạo signature cho variant dựa trên attributes
     */
    private function createVariantSignature($attributes)
    {
        $parts = [];

        foreach ($attributes as $attribute) {
            if (!isset($attribute['attribute_id']) || !isset($attribute['options'])) {
                continue;
            }

            $options = $this->normalizeOptions($attribute['options']);
            sort($options); // Sắp xếp để đảm bảo consistency

            $parts[] = $attribute['attribute_id'] . ':' . implode(',', $options);
        }

        sort($parts);
        return implode('|', $parts);
    }

    /**
     * Get validated and normalized data
     */
public function getValidatedData()
{
    $data = $this->validated();

    if (isset($data['variants']) && is_array($data['variants'])) {
        foreach ($data['variants'] as $variantIndex => &$variant) {
            if (isset($variant['attributes']) && is_array($variant['attributes'])) {
                $processedAttributes = [];

                foreach ($variant['attributes'] as $attributeId => $attribute) {
                    // Tìm attribute trong DB để đảm bảo hợp lệ
                    $attributeModel = variant_attributes::find($attribute['attribute_id'] ?? $attributeId);
                    if (!$attributeModel) continue;

                    $options = $this->normalizeOptions($attribute['options'] ?? []);

                    $processedAttributes[$attributeModel->id] = [
                        'attribute_id' => $attributeModel->id,
                        'options' => array_values($options),
                    ];
                }

                $variant['attributes'] = $processedAttributes;
            }
        }
    }

    return $data;
}

}
