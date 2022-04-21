<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    public function create($category)
    {
        Category::create($category);
    }
    public function update($category)
    {
        $_category = Category::find($category["id"]);
        $oldImage = $_category->image;
        $_category->name = $category["name"];
        $_category->image = isset($category["image"]) ? $category["image"] : $_category->image;
        $_category->active = isset($category["active"]) ? $category["active"] : $_category->active;
        $_category->save();
        return $oldImage;
    }
    public function delete($id)
    {
        $category = Category::find($id);
        $category->delete();
    }
    public function activate($id)
    {
        $category = Category::find($id);
        $category->active = 1;
        $category->save();
    }
    public function deactivate($id)
    {
        $category = Category::find($id);
        $category->active = 0;
        $category->save();
    }
    public function getActiveCategories()
    {
        return Category::where("active", 1)->get();
    }
    public function getInactiveCategories()
    {
        return Category::where("active", 0)->get();
    }
}
