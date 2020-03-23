<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::latest()->get();
        return view('admin.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:categories|min:3',
            'image' => 'mimes:jpg,png,jpeg'
        ]);

        $image = $request->file('image');
        $slug = str_slug($request->name);

        if (isset($image)) {
            $currentDate = Carbon::now()->toDateString();
            $imageName = $slug . '-' . $currentDate . uniqid() . '.' .$image->getClientOriginalExtension();

            // for category image
            if (!Storage::disk('public')->exists('category')) {
                Storage::disk('public')->makeDirectory('category');
            }

            $category = Image::make($image)->resize(1600, 479)->stream();
            Storage::disk('public')->put('category/' . $imageName, $category);

            // for slider image
            if (!Storage::disk('public')->exists('category/slider')) {
                Storage::disk('public')->makeDirectory('category/slider');
            }

            $slider = Image::make($image)->resize(500, 333)->stream();
            Storage::disk('public')->put('category/slider/' . $imageName, $slider);
        } else {
            $imageName = 'default.png';
        }
        $category = new Category();
        $category->name = $request->name;
        $category->slug = $slug;
        $category->image = $imageName;
        $category->save();

        return redirect(route('admin.category.index'))->with('successMsg', 'Category added!!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('admin.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        // dd($request->file('image'));
        $this->validate($request, [
            'name' => 'required|min:3',
            'image' => 'mimes:jpg,png,jpeg'
        ]);

        $image = $request->file('image');
        $slug = str_slug($request->name);

        if (isset($image)) {
            $currentDate = Carbon::now()->toDateString();
            $imageName = $slug . '-' . $currentDate . uniqid() . '.' .$image->getClientOriginalExtension();

            // create dir for category image
            if (!Storage::disk('public')->exists('category')) {
                Storage::disk('public')->makeDirectory('category');
            }

            // delete category image
            if (Storage::disk('public')->exists('category/' . $category->image)) {
                Storage::disk('public')->delete('category/' . $category->image);
            }

            // add category image
            $categoryImage = Image::make($image)->resize(1600, 479)->stream();
            Storage::disk('public')->put('category/' . $imageName, $categoryImage);

            // create dir for slider image
            if (!Storage::disk('public')->exists('category/slider')) {
                Storage::disk('public')->makeDirectory('category/slider');
            }

            // delete slider image
            if (Storage::disk('public')->exists('category/slider/' . $category->image)) {
                Storage::disk('public')->delete('category/slider/' . $category->image);
            }

            // add slider image
            $slider = Image::make($image)->resize(500, 333)->stream();
            Storage::disk('public')->put('category/slider/' . $imageName, $slider);
        } else {
            $imageName = $category->image;
        }

        $category->name = $request->name;
        $category->slug = $slug;
        $category->image = $imageName;
        $category->save();

        return redirect(route('admin.category.index'))->with('successMsg', 'Category updated!!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
