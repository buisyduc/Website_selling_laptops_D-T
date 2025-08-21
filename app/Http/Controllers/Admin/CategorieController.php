<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\categorie;
use App\Models\product;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
     //Show list
     public function index(Request $request) {
        $query = categorie::query()->latest('id');

        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $query->with('products')->paginate(8);

        return view('admin/Category/categories', compact('categories'));
    }
    public function renderCategoryOptions($categories, $parent_id = null, $prefix = '')
        {
            $html = '';
            foreach ($categories as $category) {
                if ($category->parent_id == $parent_id) {
                    $html .= '<option value="' . $category->id . '">' . $prefix . $category->name . '</option>';
                    $html .= $this->renderCategoryOptions($categories, $category->id, $prefix . '— ');
                }
            }
            return $html;
        }

    //create
    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
            'status' => 'boolean',
            'order' => 'integer',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
        }

        categorie::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'image' => $imagePath,
            'parent_id' => $request->parent_id,
            'status' => $request->status ?? 1, // Nếu không có thì mặc định là 1
            'order' => $request->order ?? 0,
        ]);

        return redirect()->back()->with('success', 'Category created successfully.');


    }
    public function sub_categories(Request $request) {
        $query = Categorie::query()->latest('id');

        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $query->with('products')->paginate(8);

        return view('admin/sub-categories', compact('categories'));
    }


    private function deleteCategoryAndChildren($category)
    {
        // Xóa tất cả sản phẩm thuộc danh mục hiện tại
        product::where('category_id', $category->id)->delete();

        // Duyệt và xóa tất cả danh mục con
        foreach ($category->children as $child) {
            $this->deleteCategoryAndChildren($child);
        }

        // Sau cùng xóa danh mục hiện tại
        $category->delete();
    }
    public function trashed()
    {
        $categories = Categorie::onlyTrashed()->paginate(8);

        return view('admin/Category/SoftDeletedCategories', compact('categories'));
    }

    public function destroy(Categorie $category)
    {
        $category->delete(); // xóa mềm danh mục

        return redirect()->route('categories')
                        ->with('message', 'Đã xóa mềm danh mục thành công');
    }
    public function restore($id)
    {
        $category = Categorie::withTrashed()->findOrFail($id);
        $category->restore();

        return redirect()->route('categories.trashed') // route xem danh mục đã xóa mềm
                        ->with('message', 'Khôi phục danh mục thành công');
    }
   public function forceDelete($id)
{
    $category = Categorie::withTrashed()->with('products')->findOrFail($id);

    // Nếu danh mục còn sản phẩm thì không cho xoá
    if ($category->products->count() > 0) {
        return redirect()->route('categories.trashed')
            ->with('error', 'Không thể xoá vì danh mục vẫn còn sản phẩm.');
    }

    $hasProductInChildren = Categorie::withTrashed()
        ->where('parent_id', $category->id)
        ->whereHas('products')
        ->exists();

    if ($hasProductInChildren) {
        return redirect()->route('categories.trashed')
            ->with('error', 'Không thể xoá vì có danh mục con chứa sản phẩm.');
    }

    $category->forceDelete();

    return redirect()->route('categories.trashed')
        ->with('message', 'Đã xóa vĩnh viễn danh mục.');
}


    public function edit(Categorie $category)
    {
        $allCategories = Categorie::all(); // Để hiển thị danh sách chọn parent
        return view('admin/Category/editCategory', compact('category', 'allCategories'));
    }
    public function update(Request $request, Categorie $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
            'status' => 'boolean',
            'order' => 'integer',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
            $category->image = $imagePath;
        }

        $category->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'parent_id' => $request->parent_id,
            'status' => $request->has('status') ? 1 : 0,
            'order' => $request->order ?? 0,
        ]);

        return redirect()->route('categories')->with('message', 'Cập nhật danh mục thành công');
    }
    public function restoreAll()
    {
        Categorie::onlyTrashed()->restore();

        return redirect()->back()->with('success', 'Tất cả danh mục đã được khôi phục thành công.');
    }


    public function forceDeleteAll()
    {
        // Lấy tất cả danh mục đã bị soft delete
        $categories = Categorie::onlyTrashed()->with('products')->get();

        $deletedCount = 0;
        $skippedCount = 0;

        foreach ($categories as $category) {
            if ($category->products->count() === 0) {
                $category->forceDelete(); // Xóa vĩnh viễn nếu không có sản phẩm
                $deletedCount++;
            } else {
                $skippedCount++;
            }
        }

        return redirect()->route('categories.trashed')
            ->with('message', "Đã xoá $deletedCount danh mục. Bỏ qua $skippedCount danh mục vì còn sản phẩm.");
    }





}
