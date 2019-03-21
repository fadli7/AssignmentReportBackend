<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Utilization;

class UtilizationController extends Controller
{
    public function all(User $users, Utilization $utilizations) {
        $utilizations = $utilizations
            ->with('user')
            ->get();

        return response()->json($utilizations);
    }
}
