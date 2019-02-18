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
        'assignment_desc',
    ];

    public function dispose_assignment() {
        $this->belongsTo(DisposeAssignment::class);
    }

    public function user() {
        return $this->belongsToMany(User::class);
    }
}
