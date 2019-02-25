<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssignmentUser extends Model
{
    protected $table = 'assignment_user';

    protected $fillable = [
        'user_id', 'assignment_id'
    ];

    public function assignment() {
        return $this->belongsTo(Assignment::class);
    }
}
