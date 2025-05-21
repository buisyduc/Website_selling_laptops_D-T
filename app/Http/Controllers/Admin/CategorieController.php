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

        return view('admin/categories', compact('categories'));
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
    public function destroy(Categorie $category)
    {
        $this->deleteCategoryAndChildren($category);

        return redirect()->route('categories')
                        ->with('message', 'Xóa dữ liệu thành công');
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

}
