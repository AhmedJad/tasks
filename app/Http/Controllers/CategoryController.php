<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    private $categoryRepository;
    function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    //Request inputs [name(required),image(optional),active(0 or 1) default 1]
    public function create(CreateCategoryRequest $request)
    {
        if ($request->file("image")) {
            $imageId = $request->file("image")->store("");
            $request->merge(["image" => $imageId]);
        }
        $this->categoryRepository->create($request->input());
        return ["Success Message" => "Category has been created successfully"];
    }
    public function getCategory(Category $category)
    {
        return $category;
    }
    //Request inputs [id(required),name(required),image(optional),active(0 or 1) default 1]
    public function update(UpdateCategoryRequest $request)
    {
        if ($request->file("image")) {
            $imageId = $request->file("image")->store("");
            $request->merge(["image" => $imageId]);
        }
        $oldImage = $this->categoryRepository->update($request->input());
        if ($request->file("image")) Storage::delete($oldImage);
        return ["Success Message" => "Category has been updated successfully"];
    }
    public function delete($id)
    {
        $this->categoryRepository->delete($id);
        return ["Success Message" => "Category has been deleted successfully"];
    }
    public function activate($id)
    {
        $this->categoryRepository->activate($id);
        return ["Success Message" => "Category has been activated successfully"];
    }
    public function deactivate($id)
    {
        $this->categoryRepository->deactivate($id);
        return ["Success Message" => "Category has been deactivated successfully"];
    }
    public function getActiveCategories()
    {
        return $this->categoryRepository->getActiveCategories();
    }
    public function getInactiveCategories()
    {
        return $this->categoryRepository->getInactiveCategories();
    }
}
