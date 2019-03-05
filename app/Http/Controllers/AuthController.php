<?php

namespace App\Http\Controllers;

use App\Transformers\UserTransformer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request, User $user) {
        $this->validate($request, [
            'email'         => 'required|email|unique:users',
            'password'      => 'required|min:6',
            'full_name'     => 'required',
            'place_birth'   => 'required',
            'date_birth'    => 'required',
            'role_id'       => 'required',
        ]);

        $user = $user->create([
            'email'         => $request->email,
            'password'      => bcrypt($request->password),
            'api_token'     => bcrypt($request->email),
            'full_name'     => $request->full_name,
            'place_birth'   => $request->place_birth,
            'date_birth'    => $request->date_birth,
            'role_id'       => (int)$request->role_id,
        ]);

        $response = fractal()
            ->item($user)
            ->transformWith(new UserTransformer)
            ->addMeta([
                'api_token' => $user->api_token
            ])
            ->toArray();

        return response()->json($response, 201);
    }

    public function login(Request $request, User $user) {
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(['error' => 'Your credentials is wrong'], 401);
        }

        $user = $user->find(Auth::user()->id);

//        return fractal()
//            ->item($user)
//            ->transformWith(new UserTransformer)
//            ->addMeta([
//                'token_api' => $user->api_token,
//            ])
//            ->toArray();
        return response()->json($user);
    }

    public function logout(User $user) {
        $user = Auth::user();
        $user->api_token()->revoke();

        return response()->json(['status' => 'Have Log out']);
    }
}
