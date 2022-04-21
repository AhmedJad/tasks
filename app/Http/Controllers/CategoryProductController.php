<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Models\Product;
use App\Repositories\CategoryProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryProductController extends Controller
{
    private $categoryProductRepository;
    function __construct(CategoryProductRepository $categoryProductRepository)
    {
        $this->categoryProductRepository = $categoryProductRepository;
    }
    public function createProduct(CreateProductRequest $request, $categoryId)
    {
        $request->merge($this->storeImages($request->file("images")));
        $request->merge(["category_id" => $categoryId]);
        $this->categoryProductRepository->createProduct($request->input());
        return ["Success Message" => "Product Has Been Created Successfully"];
    }
    public function getProduct(Product $product)
    {
        return $product;
    }
    public function updateProduct(Request $request)
    {
        $request->merge($this->storeImages($request->file("images")));
        $oldImages = $this->categoryProductRepository->updateProduct($request->input());
        if ($request->file("images")) {
            foreach (explode(',', $oldImages) as $image) {
                Storage::delete($image);
            }
        }
        return ["Success Message" => "Product Has Been Updated Successfully"];
    }
    public function deleteProduct($productId)
    {
        $this->categoryProductRepository->deleteProduct($productId);
        return ["Success Message" => "Product Has Been Deleted Successfully"];
    }
    public function activateProduct($id)
    {
        $this->categoryProductRepository->activateProduct($id);
        return ["Success Message" => "Product Has Been Activated Successfully"];
    }
    public function deactivateProduct($id)
    {
        $this->categoryProductRepository->deactivateProduct($id);
        return ["Success Message" => "Product Has Been deactivated Successfully"];
    }
    public function getActiveProducts()
    {
        return $this->categoryProductRepository->getActiveProducts();
    }
    public function getInactiveProducts()
    {
        return $this->categoryProductRepository->getInactiveProducts();
    }
    public function getLessThanHundredProducts()
    {
        return $this->categoryProductRepository->getLessThanHundredProducts();
    }
    public function getMoreThanHundredProducts()
    {
        return $this->categoryProductRepository->getMoreThanHundredProducts();
    }
    public function getEmptyCategories()
    {
        return $this->categoryProductRepository->getEmptyCategories();
    }
    public function getLessThanHundredCategories()
    {
        return $this->categoryProductRepository->getLessThanHundredCategories();
    }
    public function getMoreThanHundredCategories()
    {
        return $this->categoryProductRepository->getMoreThanHundredCategories();
    }
    public function getCategoriesWithProducts()
    {
        return $this->categoryProductRepository->getCategoriesWithProducts();
    }
    public function getActiveCategoriesWithActiveProducts()
    {
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
