<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Iluminate\Support\Facades\Storage;
use App\Exports\AssignmentExport;
use App\User;
use App\AssignmentUser;
use App\Assignment;
use App\History;
use App\AssignmentReport;
use App\CustomerInfo;
use App\TimeRecord;
use App\Utilization;
use App\ReportFile;

use App\Mail\NotifEmail;
use Illuminate\Support\Facades\Mail;

class AssignmentController extends Controller
{
    public function create(Request $request, Assignment $assignment,
        AssignmentUser $assignmentUser, User $user, History $history) {
        // $this->validate($request, [

        // ]);

        $assignment_id = \Carbon\Carbon::now()->format('Ymd') . mt_rand(000, 999);

        $assignment = $assignment->create([
            'id'                 => $assignment_id,
            'ptl_id'             => \Auth::user()->id,
            'project_number'     => $request->project_number,
            'io_number'          => $request->io_number,
            'assignment_class'   => $request->assignment_class,
            'assignment_tittle'  => $request->assignment_tittle,
            'assignment_desc'    => $request->assignment_desc,
            'difficulty_level'   => $request->difficulty_level,
            'status'             => "New",
        ]);

        $engineer = $request->engineer;
        $n = count($engineer);

        for ($i = 0; $i < $n; $i++ ) {
            $id = (int) $engineer[$i]['id'];
            if ($i == 0) {
                $assignmentUser->create([
                    'user_id'           => $id,
                    'assignment_id'     => $assignment_id,
                    'is_leader'         => 1,
                ]);
            } else {
                $assignmentUser->create([
                    'user_id'           => $id,
                    'assignment_id'     => $assignment_id,
                    'is_leader'         => 0,
                ]);
            }
            $user = $user->where('id', $id)->first();
            $this->send_email($user->email, $user->full_name, \Auth::user()->full_name .
                " submited you for assignment " . $assignment->assignment_tittle);

        }

        $history->create([
            'user_id'       => \Auth::user()->id,
            'assignment_id' => $assignment_id,
            'action'        => 'create assignment',
        ]);

        return response()->json($assignment);
    }

    public function listAll(Assignment $assignment, AssignmentUser $assignmentUser) {
        $assignment = $assignment
            ->with('ptl')
            ->with('user')
            ->where('is_deleted', 0)
            ->get();

        return response()->json($assignment);
    }

    public function export(Assignment $assignments) {
        return Excel::store(new AssignmentExport(), 'test.xlsx');
    }

    public function listAssignment(Assignment $assignment, AssignmentReport $assignmentReport,
        AssignmentUser $assignmentUser) {

        $id = \Auth::user()->id;

        $assignmentUser = $assignmentUser
            ->with('assignment.ptl')
            ->where('user_id', '=', $id)
            ->whereHas('assignment', function ($query) {
                return $query->where('is_deleted', 0);
            })
            ->get();

        return response()->json($assignmentUser);
    }

    public function createAR($id, Assignment $assignment, AssignmentReport $assignmentReport) {
        $assignment = $assignment->find($id);

        return response()->json($assignment);
    }

    public function submitAR(Request $request, Assignment $assignment, AssignmentReport $assignmentReport,
        CustomerInfo $customerInfo, TimeRecord $timeRecord, AssignmentUser $assignmentUser,
        History $history, User $users, ReportFile $reportFiles) {
        // $request->file('bai');
        // $bai = $request->bai->store('public/file');

        // $request->file('tnc');
        // $tnc = $request->tnc->store('public/file');

        // $request->file('photos');
        // $photos = $request->photos->store('public/image');

        // $assignmentUser = $assignmentUser
        //     ->where('user_id', \Auth::user()->id)
        //     ->where('assignment_id', $request->assignment_id)
        //     ->first();

        // \Log::info($assignmentUser);
        // \Log::info($request->assignment_id);
        // \Log::info(\Auth::user()->id);

        // ------------------------------------------------------------

        $host = $request->getHttpHost();

        // $bai = $request->bai;

        // return response()->json(sizeof($bai));

        $other_exploded = explode(',', $request->other);
        $other_decoded = base64_decode($other_exploded[1]);
        if (str_contains($other_exploded[0], 'jpeg'))
            $other_extension = 'jpeg';
        else
            $other_extension = 'png';
        $other_name = 'img/' . str_random() . '.' . $other_extension;
        $other_path = public_path() . '/' . $other_name;
        file_put_contents($other_path, $other_decoded);
        $other_loc = $host . '/' .  $other_name;

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
            'assignment_id'         => $request->assignment_id,
            'assignment_type'       => $request->assignment_type,
            'time_record_id'        => $timeRecord->id,
            'customer_info_id'      => $customerInfo->id,
            'sppd_status'           => $request->sppd_status,
            'day_number'            => $request->day_number,
            'brief_work'            => $request->brief_work,
            // 'bai'                   => $bai_loc,
            // 'tnc'                   => $tnc_loc,
            // 'photos'                => $photos_loc,
            'other'                 => $other_loc,
            'result'                => $request->result,
        ]);

        $bai = $request->bai;
        $bai_size = sizeof($bai);
        $tnc = $request->tnc;
        $tnc_size = sizeof($tnc);
        $photos = $request->photos;
        $photos_size = sizeof($photos);

        for ($i = $bai_size/2; $i < $bai_size; $i++) {
            $bai_exploded = explode(',', $bai[$i]);
            $bai_decoded = base64_decode($bai_exploded[1]);
            if (str_contains($bai_exploded[0], 'pdf'))
                $bai_extension = 'pdf';
            else if (str_contains($bai_exploded[0], 'jpg'))
                $bai_extension = 'jpg';
            else
                $bai_extension = 'png';
            $bai_name = 'file/' . str_random() . '.' . $bai_extension;
            $bai_path = public_path() . '/' . $bai_name;
            file_put_contents($bai_path, $bai_decoded);
            $bai_loc = $host . '/' . $bai_name;

            $reportFile = $reportFiles->create([
                'report_id'     => $assignmentReport->id,
                'type'          => 'bai',
                'filename'      => $bai_loc
            ]);
        }

        for ($i = $tnc_size/2; $i < $tnc_size; $i++) {
            $tnc_exploded = explode(',', $tnc[$i]);
            $tnc_decoded = base64_decode($tnc_exploded[1]);
            if (str_contains($tnc_exploded[0], 'pdf'))
                $tnc_extension = 'pdf';
            else
                $tnc_extension = 'docx';
            $tnc_name = 'file/' . str_random() . '.' . $tnc_extension;
            $tnc_path = public_path() . '/' . $tnc_name;
            file_put_contents($tnc_path, $tnc_decoded);
            $tnc_loc = $host . '/' . $tnc_name;

            $reportFile = $reportFiles->create([
                'report_id'     => $assignmentReport->id,
                'type'          => 'tnc',
                'filename'      => $tnc_loc
            ]);
        }

        for ($i = 0; $i < $photos_size; $i++) {
            $photos_exploded = explode(',', $photos[$i]);
            $photos_decoded = base64_decode($photos_exploded[1]);
            if (str_contains($photos_exploded[0], 'jpeg'))
                $photos_extension = 'jpeg';
            else
                $photos_extension = 'png';
            $photos_name = 'img/' . str_random() . '.' . $photos_extension;
            $photos_path = public_path() . '/' . $photos_name;
            file_put_contents($photos_path, $photos_decoded);
            $photos_loc = $host . '/' . $photos_name;

            $reportFile = $reportFiles->create([
                'report_id'     => $assignmentReport->id,
                'type'          => 'selfie',
                'filename'      => $photos_loc
            ]);
        }

        $history->create([
            'user_id'       => \Auth::user()->id,
            'assignment_id' => $request->assignment_id,
            'action'        => 'submit report',
        ]);

        $assignment = $assignment->where('id', $request->assignment_id)->first();

        $assignment->status = "On Progress";
        $assignment->save();

        $user = $users->where('id', $assignment->ptl_id)->first();
        $receiver_email = $user->email;
        $receiver_name = $user->full_name;
        $action = \Auth::user()->full_name . ' submit report for assignment ' . $assignment->assignment_tittle;

        $this->send_email($receiver_email, $receiver_name, $action);

        return response()->json($assignmentUser);
    }

    public function listAssignmentPTL(Assignment $assignment) {
        $assignment = $assignment
            ->where('ptl_id', \Auth::user()->id)
            ->where('is_deleted', 0)
            ->whereNotIn('status', ['Closed'])
            ->get();

        return response()->json($assignment);
    }

    public function delete_assignment (Request $request, Assignment $assignments) {
        $id = $request->id;

        $assignment = $assignments
            ->where('id', $id)
            ->first();

        \Log::info('Delete Assignment : ' . $assignment);

        $assignment->is_deleted = 1;
        $assignment->save();

        return response()->json($assignment);
    }

    public function showDetailAssignment($id, Request $request, Utilization $utilization, Assignment $assignment,
        AssignmentReport $assignmentReport) {

        $assignment = $assignment
            ->with('assignment_user.user')
            ->with('assignment_report')
            ->where('id', $id)
            ->first();

        return response()->json($assignment);
    }

    public function approve(Request $request, Utilization $utilizations, Assignment $assignments,
        AssignmentReport $assignmentReports, AssignmentUser $assignmentUsers, User $users) {

        // $engineers = $request->engineers;

        // return response()->json($engineers[0]['id']);

        $assignment = $assignments
            ->where('id', $request->assignment_id)
            ->first();

        $assignment->status = 'Closed';
        $assignment->save();

        // --------------------------------------------------------

        $engineers = $request->engineers;

        foreach ($engineers as $engineer) {
            // Set Rating
            $assignmentUser = $assignmentUsers
                ->where('assignment_id', $request->assignment_id)
                ->where('user_id', $engineer['id'])
                ->first();

            $assignmentUser->rating = $engineer['rating'];
            $assignmentUser->save();

            // Get The Utilization
            $utilization = $utilizations
                ->where('user_id', $engineer['id'])
                ->first();

            // Search Workdays Total
            $user = $users->where('id', $engineer['id'])->first();
            $strDateFrom    = $user->start_date;
            $strDateTo      =  date("Y-m-d");
            $aryRange       = array();
            $iDateFrom      = mktime(
                1,
                0,
                0,
                substr($strDateFrom, 5, 2),
                substr($strDateFrom, 8, 2),
                substr($strDateFrom, 0, 4)
            );
            $iDateTo        = mktime(
                1,
                0,
                0,
                substr($strDateTo, 5, 2),
                substr($strDateTo, 8, 2),
                substr($strDateTo, 0, 4)
            );

            if ($iDateTo >= $iDateFrom) {
                if (date("w", $iDateFrom) != 0 and date("w", $iDateFrom) != 6) {
                    array_push($aryRange, date('Y-m-d', $iDateFrom));
                }
                while ($iDateFrom < $iDateTo) {
                    $iDateFrom += 86400;

                    if (date("w", $iDateFrom) != 0 and date("w", $iDateFrom) != 6) {
                        array_push($aryRange, date('Y-m-d', $iDateFrom));
                    }
                }
            }
            $workdays_total = count($aryRange);

            // Search Total Hours
            $assignmentUser = $assignmentUsers
                ->with('assignment.assignment_report.time_record')
                ->where('user_id', $engineer['id'])
                ->whereHas('assignment', function($query) {
                    return $query->where('status', 'Closed');
                })
                ->get();

            $sumHours = 0;
            $sum_work_quality = 0;
            $iteration = 0;
            foreach ($assignmentUser as $assignmentUse) {
                $rating = $assignmentUse->rating;
                $difficulty = $assignmentUse->assignment->difficulty_level;
                $ars = $assignmentUse->assignment->assignment_report;
                foreach ($ars as $ar) {
                    $diffStartAt    = 3/4 *  (float) $this->getDiffTime(
                        $ar->time_record->time_start,
                        $ar->time_record->time_at
                    );
                    $diffAtFinish   = (float) $this->getDiffTime(
                        $ar->time_record->time_at,
                        $ar->time_record->time_job_finish
                    );
                    $diffFinishEnd  = 3/4 * (float) $this->getDiffTime(
                        $ar->time_record->time_job_finish,
                        $ar->time_record->time_end
                    );

                    // \Log::info('Finish to End ' . $diffFinishEnd);

                    $sum = (float) $diffStartAt + $diffAtFinish + $diffFinishEnd;
                    // \Log::info('Sum : ' . $sum);
                    $sumHours += $sum;
                    // $sppd++;
                }

                // Count Work Quality
                $sum = ((0.8 * $rating / 10) + (0.2 * $difficulty / 5)) * 100;
                $sum_work_quality += $sum;
                $iteration++;
            }

            // Count Sum SPPD

            $sppds = $assignmentUsers
                ->with('assignment.assignment_report.time_record')
                ->where('user_id', $engineer['id'])
                ->whereHas('assignment', function($query) {
                    return $query->where('status', 'Closed');
                })
                ->whereHas('assignment.assignment_report', function($query) {
                    return $query->where('sppd_status', 1);
                })
                ->get();

            $sum_sppd = 0;
            foreach ($sppds as $sppd) {
                foreach($sppd->assignment->assignment_report as $item) {
                    $sum_sppd++;
                }
            }

            \Log::info('Sum Work Quality : ' . $sum_work_quality);

            // Count Work Load
            $work_load      = $sumHours / $workdays_total * 100;
            if ($iteration == 0)
                $work_quality = 0;
            else
                $work_quality   = $sum_work_quality / $iteration;

            // Update Utilization
            $utilization->work_load = $work_load;
            $utilization->work_quality = $work_quality;
            $utilization->sppd = $sum_sppd;
            $utilization->complete_assignment = $iteration;
            $utilization->save();
        }

        return response()->json(['status' => 'oke']);
    }

    public function getDiffTime($startTime, $endTime) {
        $iStartTime = mktime(substr($startTime, 0, 2), substr($startTime, 3, 2), substr($startTime, 6, 2), 0, 0, 0);
        $iEndTime   = mktime(substr($endTime, 0, 2), substr($endTime, 3, 2), substr($endTime, 6, 2), 0, 0, 0);

        $diff = abs($iStartTime - $iEndTime) / 3600;

        return $diff;
    }

    public function send_email($receiver_email, $receiver_name, $action) {
        $objEmail = new \stdClass();
        $objEmail->receiver = $receiver_name;
        $objEmail->action = $action;

        Mail::to($receiver_email)->send(new NotifEmail($objEmail));

        return response()->json(["status" => "oke"]);
    }

    // public function test_email() {
    //     $objEmail = new \stdClass();
    //     $objEmail->receiver = "Fadli";
    //     $objEmail->action = "You Create File Assignment";

    //     Mail::to("fadlifarham10@gmail.com")->send(new NotifEmail($objEmail));

    //     return response()->json(["status" => "oke"]);
    // }
}
