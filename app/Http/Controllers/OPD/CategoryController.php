<?php

namespace App\Http\Controllers\OPD;

use App\Traits\File;
use Inertia\Inertia;
use App\Models\UserRole;
use Illuminate\Support\Str;
use App\Models\OPD\Category;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use XelentAbrar\HospitalAccounts\Models\Accounts\ChartOfAccount;
use App\Http\Requests\OPD\CategoryRequest;

class CategoryController extends Controller
{
    use File;

    public function __construct()
    {
        $this->middleware('checkPermission:categories.index')->only('index');
        $this->middleware('checkPermission:categories.create')->only('create', 'store');
        $this->middleware('checkPermission:categories.show')->only('show');
        $this->middleware('checkPermission:categories.edit')->only('edit', 'update');
        $this->middleware('checkPermission:categories.destroy')->only('destroy');
    }

    public function index()
    {
        $categories = Category::orderBy('created_at', 'desc')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('OPD/Category/Index', [
            'categories' => $categories,
            'role' => $role,
        ]);
    }

    public function create()
    {
        if(file_exists(base_path('config/accounts.php'))){
            $coa = ChartOfAccount::select('id','acc_desc')->get();
        }
        return Inertia::render('OPD/Category/Create', [
            'csrf_token' => csrf_token(),
            'coa' => $coa ?? null
        ]);
    }

    // public function uploadFile(Request $request)
    // {
    //     // dd($request->path);
    //     return $this->uploadImage($request, $request->prefix, $request->path,$request->name);
    //     // if ($request->hasFile('image')) {
    //     //     $file = $request->file('image');
    //     //     $originalName = $file->getClientOriginalName();
    //     //     $randomChars = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 3);
    //     //     $uniqueFilename = 'cat-' . $randomChars . '-' . $originalName;
    //     //     Storage::disk('public')->putFileAs('uploads/Inventory/category', $file, $uniqueFilename);
    //     //     return response()->json(['image' => $uniqueFilename]);
    //     // }
    //     // return '';
    // }

    private function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        if (Category::where('slug', $slug)->doesntExist()) {
            return $slug;
        }

        $existingSlugs = Category::where('slug', $slug)->pluck('slug')->toArray();
        $suffix = 2;
        while (in_array($slug . '-' . $suffix, $existingSlugs)) {
            $suffix++;
        }

        return $slug . '-' . $suffix;
    }

    public function store(CategoryRequest $request)
    {

        DB::beginTransaction();

        $uniqueFilename = $request->image;

        $data = [
            'name' => $request->name,
            'slug' => $this->generateUniqueSlug($request->name),
            'description' => $request->description,
            'image' => $uniqueFilename,
            'status' => $request->status,
            'coa_id' => $request->coa_id,
        ];
        if(file_exists(base_path('config/accounts.php'))) {
            $data['coa_id'] = $request->coa_id;
        }
        Category::create($data);

        $source = storage_path('app/public/uploads/Inventory/' . $uniqueFilename);
        $destination = public_path('uploads/Inventory/Categories/' . $uniqueFilename);

        // if (file_exists($source)) {
        //     copy($source, $destination);
        //     unlink($source);
        // }

        DB::commit();

        return redirect()->route('categories.index');
    }

    public function edit(Category $category)
    {
        if(file_exists(base_path('config/accounts.php'))){
            $coa = ChartOfAccount::select('id','acc_desc')->get();
        }
        return Inertia::render('OPD/Category/Create', [
            'category' => $category,
            'coa' => $coa ?? null,
        ]);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        DB::beginTransaction();

        $oldImage = $category->image;
        $newImage = $request->image;

        if ($oldImage !== $newImage) {

            $source = storage_path('app/public/uploads/Inventory/' . $newImage);
            $destination = public_path('uploads/Inventory/Categories/' . $newImage);

            if (file_exists($source)) {
                copy($source, $destination);
                unlink($source);
            }

            // Delete old image if it exists
            if (!empty($oldImage)) {
                $oldImagePath = public_path('uploads/Inventory/Categories/' . $oldImage);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $category->image = $newImage;
        }

        $category->name = $request->name;
        if ($category->name != $request->name) {
            $category->slug = $this->generateUniqueSlug($request->name);
        }
        $category->description = $request->description;
        $category->image = $category->image;
        $category->status = $request->status;
        $category->coa_id = $request->coa_id;

        $category->save();

        DB::commit();

        return redirect()->route('categories.index');
    }

    public function destroy(Category $category)
    {
        DB::beginTransaction();
        if ($category->subcategories()) {
            $subCategories = $category->subcategories();
            $category->subcategories()->delete();
            foreach ($subCategories as $key => $subCategory) {
                if (!empty($subCategory->image)) {
                    $oldImagePath = public_path('uploads/Inventory/sub-categories/' . $subCategory->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
            }
        }
        $category->delete();
        if (!empty($category->image)) {
            $oldImagePath = public_path('uploads/Inventory/Categories/' . $category->image);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }
        DB::commit();
        return Inertia::location(route('categories.index'));
    }
}
