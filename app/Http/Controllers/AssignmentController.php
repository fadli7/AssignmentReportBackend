<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AssignmentExport;
use App\User;
use App\AssignmentUser;
use App\Assignment;
use App\History;
use App\AssignmentReport;
use App\CustomerInfo;
use App\TimeRecord;

class AssignmentController extends Controller
{
    public function create(Request $request, Assignment $assignment,
        AssignmentUser $assignmentUser, User $user, History $history) {
        // $this->validate($request, [

        // ]);

        // \Log::info($request->engineer);

        $assignment = $assignment->create([
            'ptl_id'            => \Auth::user()->id,
            'project_number'    => $request->project_number,
            'io_number'         => $request->io_number,
            'assignment_class'  => $request->assignment_class,
            'assignment_tittle'  => $request->assignment_tittle,
            'assignment_desc'   => $request->assignment_desc,
            'status'            => "On Progress",
        ]);

        $engineer = $request->engineer;
        $n = count($engineer);
        // $engineer = implode(",", $engineer);

        // \Log::info($n);

        // return \response()->json($engineer[1]);

        for ($i = 0; $i < $n; $i++ ) {

            // $id = "user_id_" . $i;

            // \Log::info($engineer[$i]);

            $id = (int) $engineer[$i]['id'];

            // \Log::info($id);
            // \Log::info(gettype($id));
            // \dd();
            if ($i == 0) {
                $assignmentUser->create([
                    'user_id'           => $id,
                    'assignment_id'     => $assignment->id,
                    'is_leader'         => 1,
                ]);
                \Log::info('leader');

            } else {
                $assignmentUser->create([
                    'user_id'           => $id,
                    'assignment_id'     => $assignment->id,
                    'is_leader'         => 0,
                ]);
                \Log::info('not leader');

            }
        }

        $history->create([
            'user_id'       => \Auth::user()->id,
            'assignment_id' => $assignment->id,
        ]);

        return response()->json($assignment);
    }

    public function listAll(Assignment $assignment, AssignmentUser $assignmentUser) {
        // $assignment = $assignment
        //     ->join('dispose_assignment', 'assignment.id', '=','dispose_assignment.assignment_id')
        //     ->get();

        // $assignment = AssignmentUser
        //     ::join('users', 'dispose_assignment.user_id', '=', 'users.id')
        //     ->join('assignment', 'dispose_assignment.assignment_id', '=', 'assignment.id')
        //     ->get();

        $assignment = $assignment
            // ->join('users', 'assignment.ptl_id', '=', 'users.id')
            ->with('ptl')
            ->with('user')
            // ->with('assignment_user')
            ->get();

        return response()->json($assignment);
    }

    public function export(Assignment $assignments) {
        // $assignments = $assignments
        //     ->with(['ptl' => function($value) {
        //         $value->select('full_name');
        //     }])
        //     ->with(['user' => function($value) {
        //         $value->select('full_name');
        //     }])
        //     ->get();

        // return response()->json($assignments);

        return Excel::store(new AssignmentExport(), 'test.xlsx');
    }

    public function listAssignment(Assignment $assignment, AssignmentReport $assignmentReport,
        AssignmentUser $assignmentUser) {

        $id = \Auth::user()->id;
        $assignmentUser = $assignmentUser
            ->with('assignment.ptl')
            ->where('user_id', '=', $id)
            ->get();

        return response()->json($assignmentUser);
    }

    public function createAR($id, Assignment $assignment, AssignmentReport $assignmentReport) {
        $assignment = $assignment->find($id);

        return response()->json($assignment);
    }

    public function submitAR(Request $request, Assignment $assignment, AssignmentReport $assignmentReport,
        CustomerInfo $customerInfo, TimeRecord $timeRecord, AssignmentUser $assignmentUser) {
        // $request->file('bai');
        // $bai = $request->bai->store('public/file');

        // $request->file('tnc');
        // $tnc = $request->tnc->store('public/file');

        // $request->file('photos');
        // $photos = $request->photos->store('public/image');

        $assignmentUser = $assignmentUser
            ->where('user_id', \Auth::user()->id)
            ->where('assignment_id', $request->assignment_id)
            ->first();

        // ------------------------------------------------------------

        $host = $request->getHttpHost();
        $destinationFile = public_path('/file');
        $destinationImage = public_path('/img');

        $bai = $request->file('bai');
        $bai_name = rand(1, 999) . '_' . $bai->getClientOriginalName();
        // $bai_name = rand(1, 999);
        $bai_name = str_replace(' ', '-', $bai_name);
        $bai_loc = $host . '/file/' . $bai_name;
        $bai->move($destinationFile, $bai_name);

        $tnc = $request->file('tnc');
        $tnc_name = rand(1, 999) . '_' . $tnc->getClientOriginalName();
        // $tnc_name = rand(1, 999);
        $tnc_name = str_replace(' ', '-', $tnc_name);
        $tnc_loc = $host . '/file/' . $tnc_name;
        $tnc->move($destinationFile, $tnc_name);

        $photos = $request->file('photos');
        $photos_name = rand(1, 999) . '_' . $photos->getClientOriginalName();
        // $photos_name = rand(1, 999);
        $photos_name = str_replace(' ', '-', $photos_name);
        $photos_loc = $host . '/img/' . $photos_name;
        $photos->move($destinationImage, $photos_name);

        $assignment = $assignment->find($request->assignment_id);
        $assignment->status = "Waiting Approvement";
        $assignment->save();

        $timeRecord = $timeRecord->create([
            'date_work'             => $request->date_work,
            'time_start'            => $request->time_start,
            'time_at'               => $request->time_at,
            'time_job_finish'       => $request->time_job_finish,
            'time_end'              => $request->time_end
        ]);

        $customerInfo = $customerInfo->create([
            'company'               => $request->company,
            'address'               => $request->address,
            'cp'                    => $request->cp,
            'pic'                   => $request->pic
        ]);

        $assignmentReport = $assignmentReport->create([
            'assignment_user_id'    => $assignmentUser->id,
            'assignment_type'       => $request->assignment_type,
            'time_record_id'        => $timeRecord->id,
            'customer_info_id'      => $customerInfo->id,
            'sppd_status'           => $request->sppd_status,
            'day_number'            => $request->day_number,
            'brief_work'            => $request->brief_work,
            'bai'                   => $bai_loc,
            'tnc'                   => $tnc_loc,
            'photos'                => $photos_loc,
            'other'                 => $request->other,
            'result'                => $request->result,
        ]);



        // ------------------------------------------------------

        // $image = $request->file('image');
        // $imgname = time() . '.' . $image->getClientOriginalExtension();
        // $destinationPath = \public_path('/img');

        // $host = $request->getHttpHost();
        // $name = $host . '/img/' . $imgname;

        // $image->move($destinationPath, $imgname);

        return response()->json($assignmentUser);
        // return response()->json($assignmentUser);
    }

    public function listAssignmentPTL(Assignment $assignment) {
        $assignment = $assignment
            ->where('ptl_id', \Auth::user()->id)
            ->get();

        return response()->json($assignment);
    }
}
