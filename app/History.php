<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Assignment;

class History extends Model
{
    protected $table = 'history';

    protected $fillable = [
        'user_id', 'assignment_id',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function assignment() {
        return $this->belongsTo(Assignment::class);
    }

}
