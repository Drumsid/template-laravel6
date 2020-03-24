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
            $imageName = $slug . '-' . $currentDate . uniqid() . '.' . $image->getClientOriginalExtension();

            // for category image
            $this->dirExists('category');

            $categoryImage = Image::make($image)->resize(1600, 479)->stream();
            Storage::disk('public')->put('category/' . $imageName, $categoryImage);

            // for slider image
            $this->dirExists('category/slider');

            $sliderImage = Image::make($image)->resize(500, 333)->stream();
            Storage::disk('public')->put('category/slider/' . $imageName, $sliderImage);
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
            $imageName = $slug . '-' . $currentDate . uniqid() . '.' . $image->getClientOriginalExtension();

            // create dir for category image
            $this->dirExists('category');

            // delete category image
            $this->deleteImage('category/', $category->image);

            // add category image
            $categoryImage = Image::make($image)->resize(1600, 479)->stream();
            Storage::disk('public')->put('category/' . $imageName, $categoryImage);

            // create dir for slider image
            $this->dirExists('category/slider');

            // delete slider image
            $this->deleteImage('category/slider/', $category->image);

            // add slider image
            $sliderImage = Image::make($image)->resize(500, 333)->stream();
            Storage::disk('public')->put('category/slider/' . $imageName, $sliderImage);
        } else {
            $imageName = $category->image;
        }
        $category->update([
            'name' => $request->name,
            'slug' => $slug,
            'image' => $imageName,
        ]);

        return redirect(route('admin.category.index'))->with('successMsg', 'Category updated!!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category = Category::findOrFail($category->id);
        if ($category) {
            $category->delete();

            // delete category image
            $this->deleteImage('category/', $category->image);

            // delete slider image
            $this->deleteImage('category/slider/', $category->image);
        }

        return redirect(route('admin.category.index'))->with('successMsg', 'Tag deleted!!!');
    }

    public function dirExists($dir)
    {
        if (!Storage::disk('public')->exists($dir)) {
            Storage::disk('public')->makeDirectory($dir);
        }
    }

    public function deleteImage($dir, $img)
    {
        if (Storage::disk('public')->exists($dir . $img)) {
            Storage::disk('public')->delete($dir . $img);
        }
    }
}
