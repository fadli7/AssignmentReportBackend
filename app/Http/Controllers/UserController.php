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

    public function profile(User $users) {
        // $user = Auth::user();

        $user = Auth::user();

        $users = $users->with('role')->find($user->id);

        return response()->json($users);
    }

    public function engineers(User $users) {
        $users = $users
            ->where('role_id', 4)
            ->get();

        return response()->json($users);
    }

    public function update(Request $request, User $users) {
        $user = Auth::user();

        // $host = $request->getHttpHost();
        // $destination = public_path('/img');

        // $picture = $request->file('picture');
        // $picture_name = rand(1, 999) . '_' . $picture->getClientOriginalName();
        // $picture_name = str_replace(' ', '-', $picture_name);
        // $picture_loc = $host . '/file' . $picture_name;
        // $picture->move($destination, $picture_name);

        $user->password = bcrypt($request->password);
        $user->full_name = $request->full_name;
        $user->phone_number = $request->phone_number;
        $user->address = $request->address;
        $user->place_birth = $request->place_birth;
        $user->date_birth = $request->date_birth;
        $user->motto = $request->motto;
        // $user->picture = $picture_loc;
        $user->save();


        return response()->json($user);
    }
}
