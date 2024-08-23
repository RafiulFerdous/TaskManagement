<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Traits\HttpResponses;
use Carbon\Carbon;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class UserAuthenticationController extends Controller
{

    use HttpResponses;


    //=========== Login function==============


    /**
     * @throws ValidationException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = $request->authenticate();

        $token = $user->createToken($request->device_name ?? 'Task_management', [ $user->email . $user->password], now()->addDays(env('SANCTUM_TOKEN_LIFETIME')));

        $data = [];
        $data['token'] = $token->plainTextToken;
        $data['token_name'] = $token->accessToken->name;
        $data['token_type'] = 'Bearer';
        $data['created_at'] =  Carbon::parse(
            $token->accessToken->created_at
        )->toDateTimeString();
        $data['expires_at'] =  Carbon::parse(
            $token->accessToken->expires_at
        )->toDateTimeString();
        $data['user'] = $user;


        return $this->success(
            'You are successfully logged in ',
            $data,
        );


    }






//================= Logout function ==============

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'You are logged out'
        ];
    }


//=============Register Function=================


    public function registration(RegisterRequest $request)
    {
        $request->validated($request->all());

        try {
            DB::beginTransaction();
            if (request()->hasFile('image')) {
                $image = $request['image'];
                $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
                Image::make($image)->resize(400, 400)->save('upload/user-profile/' . $name_gen);
                $save_url = 'upload/user-profile/' . $name_gen;
            } else {
                $save_url = 'upload/Avatar/avatar.png';
            }

            $user = new User;
            $user->firstname = $request['firstname'];
            $user->lastname = $request['lastname'];
            $user->username = $request['username'];
            $user->role_id = 3;
            $user->email = $request['email'];
            $user->image = $save_url;
            $user->password = Hash::make($request['password']);
            $user->save();

            DB::commit();

            // $data = compact('user');
            return $this->success(
                'User Created Successfully !',
                $user

            );
        } catch (Throwable $e) {
//            return $e;

            return response()->json([
                'message' => 'Registration failed!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


}
