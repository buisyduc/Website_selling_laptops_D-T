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

        return view('admin/brands', compact('brands'));
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
}
