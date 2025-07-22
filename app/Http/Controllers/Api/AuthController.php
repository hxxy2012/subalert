<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nickname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'message' => '验证失败',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'nickname' => $request->nickname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
        ]);

        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'code' => 201,
            'message' => '注册成功',
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'message' => '验证失败',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'code' => 401,
                'message' => '邮箱或密码错误'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        if ($user->status !== 'active') {
            return response()->json([
                'code' => 403,
                'message' => '账户已被禁用'
            ], 403);
        }

        // 更新最后登录时间
        $user->update(['last_login_at' => now()]);

        // 删除之前的令牌（可选）
        $user->tokens()->delete();

        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'code' => 200,
            'message' => '登录成功',
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'code' => 200,
            'message' => '退出成功'
        ]);
    }

    public function refresh(Request $request)
    {
        $user = $request->user();
        
        // 删除当前令牌
        $request->user()->currentAccessToken()->delete();
        
        // 创建新令牌
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'code' => 200,
            'message' => '令牌刷新成功',
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => $request->user()
        ]);
    }
}