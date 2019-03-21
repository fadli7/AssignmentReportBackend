<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportFile extends Model
{
    protected $table = 'report_files';

    protected $fillable = [
        'report_id', 'type', 'filename'
    ];
}
