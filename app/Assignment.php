<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\DisposeAssignment;

class Assignment extends Model
{
    protected $table = 'assignment';

    protected $fillable = [
        'ptl_id', 'project_number', 'io_number', 'assignment_class', 'assignment_tittle',
        'assignment_desc', 'status',
    ];

    public function assignment_user() {
        $this->hasMany(AssignmentUser::class);
    }

    public function user() {
        return $this->belongsToMany(User::class);
    }

    public function ptl() {
        return $this->belongsTo(User::class);
    }
}
