<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories;
use App\Http\Requests\CatUpdateRequest;
use Illuminate\Support\Facades\Storage;

class CategoriesController extends Controller
{
    public function index(Request $request)
    {
        $categories = Categories::when($request->search, function($q) use($request){

            return $q->where('name' , 'like' ,'%' .$request->search. '%');

        })->paginate(10);
        $search = Categories::select('name')->orderBy('name')->get();
        return view('categories.index',compact('categories','search'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $image_path = '';

        if ($request->hasFile('image')) {
            $image_path = $request->file('image')->store('products', 'public');
        }

        $category = Categories::create([
            'name' => $request->name,
            'image' => $image_path,
        ]);

        if (!$category) {
            return redirect()->back()->with('error', 'Sorry, there a problem while creating category.');
        }
        return redirect()->route('categories.index')->with('success', 'Success, you category have been created.');
    }

    public function show($id)
    {
        //
    }

    public function edit( $id)
    {
        $category = Categories::findOrFail($id);
        return view('categories.edit',compact('category'));
    }


    public function update(Request $request, Categories $category)
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ]);
        $category->name = $request->name;

        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image) {
                Storage::delete($category->image);
            }
            // Store image
            $image_path = $request->file('image')->store('products', 'public');
            // Save to Database
            $category->image = $image_path;
        }

        if (!$category->save()) {
            return redirect()->back()->with('error', 'Sorry, there\'re a problem while updating category.');
        }
        return redirect()->route('categories.index')->with('success', 'Success, your category have been updated.');
    }

    public function destroy(Categories $category)
    {
        if ($category->image) {
            Storage::delete($category->image);
        }
        $category->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
