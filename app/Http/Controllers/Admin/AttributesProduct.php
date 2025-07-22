<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\product;
use App\Models\product_variant_options;
use App\Models\product_variants;
use App\Models\variant_attributes;
use App\Models\variant_options;
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
        $attributes = $attributes->paginate(20);
        $allAttributes = variant_attributes::all(); // lấy tất cả để hiển thị danh mục cha

        return view('admin/Attributes/attributes', compact('attributes', 'allAttributes', 'products', 'variants'));
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
   public function store(Request $request){
    $request->validate([
        'name' => 'required|string|max:255|unique:variant_attributes,name',
        'parent_id' => 'nullable|exists:variant_attributes,id',
        'values' => 'required|array|min:1',
        'values.*' => 'required|string|max:255',
    ]);

    // Tạo thuộc tính
    $attribute = variant_attributes::create([
        'name' => $request->name,
        'parent_id' => $request->parent_id,
    ]);

    // Tạo các giá trị tương ứng
   foreach ($request->values as $value) {
    variant_options::create([
        'attribute_id' => $attribute->id,
        'value' => $value,
    ]);
}

    return redirect()->back()->with('success', 'Thuộc tính và các giá trị đã được tạo thành công!');
}


      public function destroy(variant_attributes $variant_attributes)
    {
        $variant_attributes->delete(); // xóa mềm

        return redirect()->route('attributes')
                        ->with('message', 'Đã xóa mềm biến thể thành công');
    }
         public function trashed()
    {
        $variant_attributes = variant_attributes::onlyTrashed()->paginate(8);

        return view('admin/Attributes/SoftDeletedAttributes', compact('variant_attributes'));
    }




        // Hiển thị danh sách các biến thể đã xóa mềm


    // Khôi phục 1 biến thể
    public function restore($id)
    {
        $attribute = variant_attributes::onlyTrashed()->findOrFail($id);
        $attribute->restore();

        return redirect()->back()->with('message', 'Đã khôi phục biến thể thành công!');
    }

    // Xóa vĩnh viễn 1 biến thể
    public function forceDelete($id)
    {
        $attribute = variant_attributes::onlyTrashed()->findOrFail($id);
        $attribute->forceDelete();

        return redirect()->back()->with('message', 'Đã xóa vĩnh viễn biến thể!');
    }

    // Khôi phục tất cả
    public function restoreAll()
    {
        variant_attributes::onlyTrashed()->restore();

        return redirect()->back()->with('message', 'Đã khôi phục tất cả biến thể!');
    }

    // Xóa vĩnh viễn tất cả
    public function forceDeleteAll()
    {
        variant_attributes::onlyTrashed()->forceDelete();

        return redirect()->back()->with('message', 'Đã xóa vĩnh viễn tất cả biến thể!');
    }
    public function edit($id)
    {
        $attribute = variant_attributes::with('options')->findOrFail($id);
        return view('admin/Attributes/editAttributes', compact('attribute'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'values' => 'required|array|min:1',
            'values.*' => 'required|string|max:255',
        ]);

        $attribute = variant_attributes::findOrFail($id);
        $attribute->update(['name' => $request->name]);

        // Cập nhật lại toàn bộ giá trị
        $attribute->options()->delete();
        foreach ($request->values as $value) {
            $attribute->options()->create(['value' => $value]);
        }

        return redirect()->route('attributes.edit', $attribute->id)
                        ->with('success', 'Cập nhật thuộc tính thành công!');
    }



}
