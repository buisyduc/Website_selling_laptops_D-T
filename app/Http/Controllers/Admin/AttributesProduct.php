<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\product;
use App\Models\product_variants;
use App\Models\variant_attributes;
use Illuminate\Http\Request;

class AttributesProduct extends Controller
{
    public function index(Request $request)
    {
        $query = variant_attributes::query()->latest('id');

        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $variants  = product_variants::all();
        $products = product::all();
        $attributes = variant_attributes::query()->latest('id');
        $attributes = $attributes->paginate(8);
        $allAttributes = variant_attributes::all(); // lấy tất cả để hiển thị danh mục cha

        return view('admin/Product/attributes', compact('attributes', 'allAttributes', 'products', 'variants'));
    }

    public function renderCategoryOptions($attributes, $parent_id = null, $prefix = '')
    {
        $html = '';
        foreach ($attributes as $attribute) {
            if ($attribute->parent_id == $parent_id) {
                $html .= '<option value="' . $attribute->id . '">' . $prefix . $attribute->name . '</option>';
                $html .= $this->renderCategoryOptions($attributes, $attribute->id, $prefix . '— ');
            }
        }
        return $html;
    }

    //create
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:variant_attributes,id',
        ]);

        variant_attributes::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id,


        ]);

        return redirect()->back()->with('success', 'Attributes created successfully.');
    }
}
