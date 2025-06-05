<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Hoặc thêm logic authorization tùy theo yêu cầu
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $productId = $this->route('id'); // Lấy ID từ route parameter

        return [
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'slug')->ignore($productId)
            ],
            'description' => 'required|string',
            'brand_id' => 'required|integer|exists:brands,id',
            'category_id' => 'required|integer|exists:categories,id',
            'status' => 'nullable|boolean',
            'release_date' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',

            // Validation cho variants
            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|integer|exists:product_variants,id',
            'variants.*.sku' => 'nullable|string|max:100',
            'variants.*.price' => 'required_with:variants|numeric|min:0',
            'variants.*.compare_price' => 'nullable|numeric|min:0',
            'variants.*.cost_price' => 'nullable|numeric|min:0',
            'variants.*.quantity' => 'required_with:variants|integer|min:0',
            'variants.*.weight' => 'nullable|numeric|min:0',
            'variants.*.status' => 'nullable|boolean',
            'variants.*.attributes' => 'nullable|array',
            'variants.*.attributes.*.attribute_id' => 'required_with:variants.*.attributes|integer|exists:variant_attributes,id',
            'variants.*.attributes.*.option_id' => 'required_with:variants.*.attributes|integer|exists:variant_attribute_options,id',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Tên sản phẩm là bắt buộc.',
            'name.max' => 'Tên sản phẩm không được vượt quá :max ký tự.',
            'slug.required' => 'Slug là bắt buộc.',
            'slug.unique' => 'Slug này đã được sử dụng.',
            'description.required' => 'Mô tả sản phẩm là bắt buộc.',
            'brand_id.required' => 'Thương hiệu là bắt buộc.',
            'brand_id.exists' => 'Thương hiệu không hợp lệ.',
            'category_id.required' => 'Danh mục là bắt buộc.',
            'category_id.exists' => 'Danh mục không hợp lệ.',
            'image.image' => 'File phải là hình ảnh.',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp.',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
            'images.*.image' => 'Các file phải là hình ảnh.',
            'images.*.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp.',
            'images.*.max' => 'Kích thước mỗi hình ảnh không được vượt quá 2MB.',

            // Messages cho variants
            'variants.*.price.required_with' => 'Giá là bắt buộc khi có biến thể.',
            'variants.*.price.numeric' => 'Giá phải là số.',
            'variants.*.price.min' => 'Giá không được nhỏ hơn 0.',
            'variants.*.quantity.required_with' => 'Số lượng là bắt buộc khi có biến thể.',
            'variants.*.quantity.integer' => 'Số lượng phải là số nguyên.',
            'variants.*.quantity.min' => 'Số lượng không được nhỏ hơn 0.',
        ];
    }

    /**
     * Get validated data with custom processing.
     */
    public function getValidatedData(): array
    {
        $validated = $this->validated();

        // Xử lý slug nếu không được cung cấp
        if (empty($validated['slug']) && !empty($validated['name'])) {
            $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        }

        // Xử lý status
        $validated['status'] = $validated['status'] ?? 1;

        // Xử lý variants
        if (isset($validated['variants'])) {
            foreach ($validated['variants'] as &$variant) {
                $variant['status'] = $variant['status'] ?? 1;
            }
        }

        return $validated;
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Custom validation logic có thể thêm ở đây

            // Ví dụ: Kiểm tra compare_price phải lớn hơn price
            if (isset($this->variants)) {
                foreach ($this->variants as $index => $variant) {
                    if (isset($variant['compare_price']) && isset($variant['price'])) {
                        if ($variant['compare_price'] <= $variant['price']) {
                            $validator->errors()->add(
                                "variants.{$index}.compare_price",
                                'Giá so sánh phải lớn hơn giá bán.'
                            );
                        }
                    }
                }
            }
        });
    }
}
