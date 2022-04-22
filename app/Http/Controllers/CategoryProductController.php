<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Imports\ProductImport;
use App\Models\Product;
use App\Repositories\CategoryProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class CategoryProductController extends Controller
{
    private $categoryProductRepository;
    function __construct(CategoryProductRepository $categoryProductRepository)
    {
        $this->categoryProductRepository = $categoryProductRepository;
    }
    /*
    Request inputs [name(required),images(optional , array of images)
    ,active(0 or 1) default 1 , price (optional) , description(optional)]
    */
    public function createProduct(CreateProductRequest $request, $categoryId)
    {
        $this->authorize("role", [User::class, ["Admin", "Employee"]]);
        $request->merge($this->storeImages($request->file("images")));
        $request->merge(["category_id" => $categoryId]);
        $this->categoryProductRepository->createProduct($request->input());
        return ["Success Message" => "Product Has Been Created Successfully"];
    }
    public function createProductsFromExcel(Request $request)
    {
        $this->authorize("role", [User::class, ["Admin", "Employee"]]);
        Excel::import(new ProductImport(), $request->file('products'));
        return "Products Has Been Created From Excel Successfully";
    }
    public function getProduct(Product $product)
    {
        $this->authorize("role", [User::class, ["Admin", "Employee"]]);
        return $product;
    }
    /*
    Request inputs [id(required),name(required),images(optional , array of images)
    ,active(0 or 1) default 1 , price (optional) , description(optional)]
    */
    public function updateProduct(UpdateProductRequest $request)
    {
        $this->authorize("role", [User::class, ["Admin", "Employee"]]);
        $request->merge($this->storeImages($request->file("images")));
        $oldImages = $this->categoryProductRepository->updateProduct($request->input());
        if ($request->file("images") && $oldImages) {
            foreach (explode(',', $oldImages) as $image) {
                Storage::delete($image);
            }
        }
        return ["Success Message" => "Product Has Been Updated Successfully"];
    }
    public function deleteProduct($productId)
    {
        $this->authorize("role", [User::class, ["Admin", "Employee"]]);
        $this->categoryProductRepository->deleteProduct($productId);
        return ["Success Message" => "Product Has Been Deleted Successfully"];
    }
    public function activateProduct($id)
    {
        $this->authorize("role", [User::class, ["Admin", "Employee"]]);
        $this->categoryProductRepository->activateProduct($id);
        return ["Success Message" => "Product Has Been Activated Successfully"];
    }
    public function deactivateProduct($id)
    {
        $this->authorize("role", [User::class, ["Admin", "Employee"]]);
        $this->categoryProductRepository->deactivateProduct($id);
        return ["Success Message" => "Product Has Been deactivated Successfully"];
    }
    public function getActiveProducts()
    {
        $this->authorize("role", [User::class, ["Admin", "Employee"]]);
        return $this->categoryProductRepository->getActiveProducts();
    }
    public function getInactiveProducts()
    {
        $this->authorize("role", [User::class, ["Admin", "Employee"]]);
        return $this->categoryProductRepository->getInactiveProducts();
    }
    public function getLessThanHundredProducts()
    {
        $this->authorize("role", [User::class, ["Admin", "Employee"]]);
        return $this->categoryProductRepository->getLessThanHundredProducts();
    }
    public function getMoreThanHundredProducts()
    {
        $this->authorize("role", [User::class, ["Admin", "Employee"]]);
        return $this->categoryProductRepository->getMoreThanHundredProducts();
    }
    public function getEmptyCategories()
    {
        $this->authorize("role", [User::class, ["Admin", "Employee"]]);
        return $this->categoryProductRepository->getEmptyCategories();
    }
    public function getLessThanHundredCategories()
    {
        $this->authorize("role", [User::class, ["Admin", "Employee"]]);
        return $this->categoryProductRepository->getLessThanHundredCategories();
    }
    public function getMoreThanHundredCategories()
    {
        $this->authorize("role", [User::class, ["Admin", "Employee"]]);
        return $this->categoryProductRepository->getMoreThanHundredCategories();
    }
    public function getCategoriesWithProducts()
    {
        $this->authorize("role", [User::class, ["Admin", "Employee"]]);
        return $this->categoryProductRepository->getCategoriesWithProducts();
    }
    public function getActiveCategoriesWithActiveProducts()
    {
        $this->authorize("role", [User::class, ["Admin", "Employee"]]);
        return $this->categoryProductRepository->getActiveCategoriesWithActiveProducts();
    }
    //Commons
    private function storeImages($imagesFiles)
    {
        $images = "";
        if ($imagesFiles) {
            foreach ($imagesFiles as $index => $image) {
                $imageId = $image->store("");
                $images .= $imageId . ($index == array_key_last($imagesFiles) ? "" : ",");
            }
        }
        return ["images" => $images ? $images : null];
    }
}
