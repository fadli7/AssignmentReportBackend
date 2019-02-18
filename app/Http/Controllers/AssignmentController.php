<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\AssignmentUser;
use App\Assignment;
use App\History;

class AssignmentController extends Controller
{
    public function create(Request $request, Assignment $assignment,
        AssignmentUser $assignmentUser, User $user, History $history) {
        // $this->validate($request, [

        // ]);

        $assignment = $assignment->create([
            'ptl_id'            => \Auth::user()->id,
            'project_number'    => $request->project_number,
            'io_number'         => $request->io_number,
            'assignment_class'  => $request->assignment_class,
            'assignment_tittle'  => $request->assignment_tittle,
            'assignment_desc'   => $request->assignment_desc
        ]);

        $n = (int)$request->sum_engineer;

        \Log::info($n);

        for ($i = 0; $i < $n; $i++ ) {

            $id = "user_id_" . $i;

            \Log::info($id);


            if ($i == 0) {
                $assignmentUser->create([
                    'user_id'           => $request->$id,
                    'assignment_id'     => $assignment->id,
                    'is_leader'         => 1,
                ]);
                // \Log::info('leader');

            } else {
                $assignmentUser->create([
                    'user_id'           => $request->$id,
                    'assignment_id'     => $assignment->id,
                ]);
                // \Log::info('not leader');

            }
        }

        $history->create([
            'user_id'       => \Auth::user()->id,
            'assignment_id' => $assignment->id,
        ]);

        return response()->json($assignment);
    }

    public function list(Assignment $assignment, AssignmentUser $assignmentUser) {
        // $assignment = $assignment
        //     ->join('dispose_assignment', 'assignment.id', '=','dispose_assignment.assignment_id')
        //     ->get();

        // $assignment = AssignmentUser
        //     ::join('users', 'dispose_assignment.user_id', '=', 'users.id')
        //     ->join('assignment', 'dispose_assignment.assignment_id', '=', 'assignment.id')
        //     ->get();

        $assignment = $assignment
            ->join('users', 'assignment.ptl_id', '=', 'users.id')
            ->with('user')
            ->get();

        return response()->json($assignment);
    }
}
