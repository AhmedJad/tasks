<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetPassword;
use App\Mail\ForgetPassword;
use App\Models\User;
use App\Repositories\AuthRepository;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    private $authRepository;
    function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }
    /*
    Request inputs [email and password are required fields]    
    */
    public function login()
    {
        $credentials = request(['email', 'password']);
        //Add cradential active ->user should pass if he is active
        $credentials["active"] = 1;
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    //Make the generated token invalid then you need to login again to generate new valid token
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }
    public function forgetPassword(User $user)
    {
        $token = Str::random(40);
        $this->authRepository->insertResetPassword($user->email, $token);
        Mail::to($user->email)->send(new ForgetPassword(['user' => $user, 'token' => $token]));
        return ["Success Message" => "Token has been sent to your email"];
    }
    public function resetPassword(ResetPassword $request)
    {
        $passwordReset = $this->authRepository->getPasswordReset($request->token, 15);
        if (empty($passwordReset)) {
            return response()->json(["error" => "Token isn't valid"], 400);
        }
        $this->authRepository->changePassword($request->password, $passwordReset->email);
        return ["Success Message" => "Password Has Been Changed Successfully"];
    }
    //Commons
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
