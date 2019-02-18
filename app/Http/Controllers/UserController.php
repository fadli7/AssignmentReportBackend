<?php

namespace App\Http\Controllers;

use App\Transformers\UserTransformer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function users(User $user) {
        $users = $user->all();

        return fractal()
            ->collection($users)
            ->transformWith(new UserTransformer)
            ->toArray();
    }

    public function user(User $user) {
        $user = Auth::user();

        return response()->json(["user" => $user]);
    }

    public function profile() {
        $user = Auth::user();

        return response()->json($user);
    }
}
