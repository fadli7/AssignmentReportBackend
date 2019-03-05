<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\History;
use App\User;

class HistoryController extends Controller
{
    public function recent(History $history) {
        $history = $history
            ->with('user')
            ->with('assignment')
            ->get();

        return response()->json($history);
    }

    public function idle(User $user) {
        $user = $user
            ->whereDoesntHave('assignment')
            ->where('role_id', '4')
            ->get();

        return response()->json($user);
    }
}
