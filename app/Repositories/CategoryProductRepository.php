<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Product;

class CategoryProductRepository
{
    public function createProduct($product)
    {
        Product::create($product);
    }
    public function deleteProduct($productId)
    {
        Product::find($productId)->delete();
    }
    public function updateProduct($product)
    {
        $_product = Product::find($product["id"]);
        $oldImages = $_product->images;
        $_product->name = $product["name"];
        $_product->description = isset($product["description"]) ? $product["description"]
            : $_product->description;
        $_product->active = isset($product["active"]) ? $product["active"] : $_product->active;
        $_product->price = isset($product["price"]) ? $product["price"] : $_product->price;
        $_product->images = isset($product["images"]) ? $product["images"] : $_product->images;
        $_product->save();
        return $oldImages;
    }
    public function activateProduct($id)
    {
        $product = Product::find($id);
        $product->active = 1;
        $product->save();
    }
    public function deactivateProduct($id)
    {
        $product = Product::find($id);
        $product->active = 0;
        $product->save();
    }
    public function getActiveProducts()
    {
        return Product::where("active", 1)->get();
    }
    public function getInactiveProducts()
    {
        return Product::where("active", 0)->get();
    }
    public function getLessThanHundredProducts()
    {
        return Product::where("price", "<", 100.00)->get();
    }
    public function getMoreThanHundredProducts()
    {
        return Product::where("price", ">", 100.00)->get();
    }
    public function getEmptyCategories()
    {
        return Category::doesntHave("products")->get();
    }
    public function getLessThanHundredCategories()
    {
        return Category::whereHas("products", function ($query) {
            $query->where("price", "<", "100");
        })->get();
    }
    public function getMoreThanHundredCategories()
    {
        return Category::whereHas("products", function ($query) {
            $query->where("price", ">", "100");
        })->get();
    }
    public function getCategoriesWithProducts()
    {
        return Category::with("products")->get();
    }
    public function getActiveCategoriesWithActiveProducts()
    {
        return Category::with(["products" => function ($query) {
            $query->where("active", 1);
        }])->where("active", 1)->get();
    }
}
