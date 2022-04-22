<?php

namespace App\Repositories;

use App\Models\ShoppingCart;
use App\Models\User;

class UserRepository
{
    public function createUser($user)
    {
        User::create($user);
    }
    public function activateUser($id)
    {
        $user = User::find($id);
        $user->active = 1;
        $user->save();
    }
    public function deactivateUser($id)
    {
        $user = User::find($id);
        if ($user->type != "Admin") {
            $user->active = 0;
            $user->save();
        }
    }
    public function updateUser($user)
    {
        $_user = User::find($user["id"]);
        $oldImage = $_user->image;
        $_user->name = $user["name"];
        $_user->image = isset($user["image"]) ? $user["image"] : $_user->image;
        $_user->phone = isset($user["phone"]) ? $user["phone"] : $_user->phone;
        $_user->address = isset($user["address"]) ? $user["address"] : $_user->address;
        $_user->active = isset($user["active"]) ? $user["active"] : $_user->active;
        $_user->type = isset($user["type"]) ? $user["type"] : $_user->type;
        $_user->save();
        return $oldImage;
    }
    public function addToCart($shoppingCart)
    {
        return ShoppingCart::create($shoppingCart);
    }
    public function deleteProductFromCart($userId, $productId)
    {
        ShoppingCart::where("user_id", $userId)->where("product_id", $productId)->delete();
    }
    public function editProfile($user)
    {
        $_user = User::find($user["id"]);
        $oldImage = $_user->image;
        $_user->name = $user["name"];
        $_user->image = isset($user["image"]) ? $user["image"] : $_user->image;
        $_user->phone = isset($user["phone"]) ? $user["phone"] : $_user->phone;
        $_user->address = isset($user["address"]) ? $user["address"] : $_user->address;
        $_user->save();
        return $oldImage;
    }
}
