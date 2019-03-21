<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\DisposeAssignment;

class Assignment extends Model
{
    public $incrementing = false;
    public $primaryKey = 'id';

    protected $table = 'assignment';

    protected $fillable = [
        'id', 'ptl_id', 'project_number', 'io_number', 'assignment_class', 'assignment_tittle',
        'assignment_desc', 'status', 'difficulty_level',
    ];

    public function assignment_user() {
        return $this->hasMany(AssignmentUser::class);
    }

    public function assignment_report() {
        return $this->hasMany(AssignmentReport::class);
    }

    public function user() {
        return $this->belongsToMany(User::class);
    }

    public function ptl() {
        return $this->belongsTo(User::class);
    }
}
