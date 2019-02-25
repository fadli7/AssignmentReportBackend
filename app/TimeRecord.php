<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimeRecord extends Model
{
    protected $table = 'time_record';

    protected $fillable = [
        'date_work', 'time_start', 'time_at', 'time_job_finish', 'time_end'
    ];
}
