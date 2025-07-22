<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(request $request){
        $query = brand::query()->latest('id');

        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $brands = $query->paginate(8);

        return view('admin/Brand/brands', compact('brands'));
    }

    //create
    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:brands,slug',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('brands', 'public');
        }

        brand::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'logo' => $logoPath,

        ]);

        return redirect()->back()->with('success', 'Brand created successfully.');


    }

      public function destroy(brand $brand)
    {
        $brand->delete(); // xóa mềm

        return redirect()->route('brands')
                        ->with('message', 'Đã xóa mềm thương hiệu thành công');
    }


     public function restore($id)
    {
        $brands = brand::withTrashed()->findOrFail($id);
        $brands->restore();

        return redirect()->route('brands.trashed') // route xem danh mục đã xóa mềm
                        ->with('message', 'Khôi phục danh mục thành công');
    }
    public function forceDelete($id)
    {
        $brands = brand::withTrashed()->findOrFail($id);
        $brands->forceDelete();

        return redirect()->route('brands.trashed')
                        ->with('message', 'Đã xóa vĩnh viễn thương hiệu');
    }

    //hiển thị danh sách những thương hiệu đã xóa
     public function trashed()
    {
        $brands = brand::onlyTrashed()->paginate(8);

        return view('admin/Brand/SoftDeletedBrand', compact('brands'));
    }

      public function restoreAll()
    {
        brand::onlyTrashed()->restore();

        return redirect()->back()->with('success', 'Tất cả thương hiệu đã được khôi phục thành công.');
    }
    public function forceDeleteAll()
    {
        $brands  = brand::onlyTrashed()->get();

        foreach ($brands as $brand) {
            $brand->forceDelete();
        }

        return redirect()->back()->with('success', 'Đã xóa vĩnh viễn tất cả thương hiệu.');
    }

    public function edit(brand $brand)
    {
        $allBrand = brand::all(); // Để hiển thị danh sách chọn parent
        return view('admin/Brand/editBrand', compact('brand', 'allBrand'));
    }

public function update(Request $request, Brand $brand)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:brands,slug,' . $brand->id,
        'description' => 'nullable|string',
        'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Nếu có upload ảnh mới
    if ($request->hasFile('logo')) {
        $logoPath = $request->file('logo')->store('brands', 'public');
        $brand->logo = $logoPath;
    }

    // Cập nhật các trường khác
    $brand->name = $request->name;
    $brand->slug = $request->slug;
    $brand->description = $request->description;

    $brand->save();

    return redirect()->route('brands')->with('message', 'Cập nhật thương hiệu thành công');
}



}
