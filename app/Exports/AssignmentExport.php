<?php

namespace App\Exports;

use App\Assignment;
use App\Users;
use Maatwebsite\Excel\Concerns\FromCollection;

class AssignmentExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Assignment
            // ::join('users', 'assignment.ptl_id', '=', 'users.id')
            // ->select()
            ::with('ptl')
            ->with('user')
            ->select(
                'id',
                'project_number',
                'io_number',
                'assignment_class',
                'assignment_tittle',
                'assignment_desc',
            )
            ->get();
    }

    public function headings() : array {

    }
}
