<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Utilization extends Model
{
    protected $table = "utilization";

    protected $fillable = [
        'work_load', 'user_id' ,'work_quality', 'sppd', 'complete_assignment'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
