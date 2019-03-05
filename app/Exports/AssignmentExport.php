<?php

namespace App\Exports;

use App\Assignment;
use App\Users;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AssignmentExport implements FromCollection, WithHeadings
{
    public function makeToArray() {

    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $assignments = Assignment
            ::with('ptl')
            ->with('user')
            ->get();

        $data = array();

        $i = 0;
        foreach($assignments as &$assignment) {
            $temp = array(
                'id'                => $assignment['id'],
                'ptl'               => $assignment['ptl']['first_name'],
                'project_number'    => $assignment['project_number'],
                'io_number'         => $assignment['io_number'],
                'assignment_title'  => $assignment['assignment_tittle'],
                'status'            => $assignment['status']
            );

            // $temp = array();
            // $temp['id'] = $assignment[$i]['id'];
            // $temp['PTL'] = $assignment[$i]['ptl']['first_name'];
            // $temp['Project_Number'] = $assignment[$i]['project_number'];
            // $temp['IO_Number'] = $assignment[$i]['io_number'];
            // $temp['Assignment_Title'] = $assignment[$i]['assignment_tittle'];

            $data[$i] = $temp;
            $i++;
        }

        return collect($data);

    }

    public function headings(): array {
        return [
            '#',
            'PTL',
            'Project Number',
            'IO Number',
            'Assignment Titte',
            'Status',
        ];
    }
}
