<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\ShoppingCartRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\ShoppingCart;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    private $userRepository;

    function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    /*
    Request inputs [name(required) ,email(required,email,unique), password(required) , image(optioanl) ,phone(optional)
    ,address(optional),type(required must be Employee/Client),active (0 or 1 default 1)]
    */
    public function createUser(CreateUserRequest $request)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        if ($request->file("image")) {
            $imageId = $request->file("image")->store("");
            $request->merge(["image" => $imageId]);
        }
        $this->userRepository->createUser($request->input());
        return ["Success Message" => "User Has Been Created Successfully"];
    }
    /*
    Request inputs [id(required),name(required),image(optioanl) ,phone(optional)
    ,address(optional),type(required must be Employee/Client),active (0 or 1 default 1)]
    */
    public function updateUser(UpdateUserRequest $request)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        if ($request->file("image")) {
            $imageId = $request->file("image")->store("");
            $request->merge(["image" => $imageId]);
        }
        $oldImage = $this->userRepository->updateUser($request->input());
        if ($request->file("image") && $oldImage) Storage::delete($oldImage);
        return ["Success Message" => "User Has Been Updated Successfully"];
    }
    public function getUser(User $user)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        return $user;
    }
    public function deleteUser(User $user)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        if ($user->type != "Admin") $user->delete();
        return ["Success Message" => "User Has Been Deleted Successfully"];
    }
    public function activateUser($id)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $this->userRepository->activateUser($id);
        return ["Success Message" => "User Has Been Activated Successfully"];
    }
    public function deactivateUser($id)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $this->userRepository->deactivateUser($id);
        return ["Success Message" => "User Has Been Deactivated Successfully"];
    }
    //Request input [user_id (required) , product_id (required) , quantity (required)]
    public function addToCart(ShoppingCartRequest $request)
    {
        $this->authorize("role", [User::class, ["Client"]]);
        $request->merge(["user_id" => $request->user()->id]);
        return $this->userRepository->addToCart($request->input());
    }
    public function deleteProductFromCart(ShoppingCart $shoppingCart)
    {
        $this->authorize("role", [User::class, ["Client"]]);
        $shoppingCart->delete();
        return ["Success Message" => "Product has been removed from the cart successfully"];
    }
    /*
    Request inputs [name(required),image(optioanl) ,phone(optional)
    ,address(optional)]
    */
    public function editProfile(ProfileRequest $request)
    {
        $this->authorize("role", [User::class, ["Admin", "Employee", "Client"]]);
        if ($request->file("image")) {
            $imageId = $request->file("image")->store("");
            $request->merge(["image" => $imageId]);
        }
        $request->merge(["id" => $request->user()->id]);
        $oldImage = $this->userRepository->editProfile($request->input());
        if ($request->file("image") && $oldImage) Storage::delete($oldImage);
        return ["Success Message" => "Profile Has Been Updated Successfully"];
    }
}
