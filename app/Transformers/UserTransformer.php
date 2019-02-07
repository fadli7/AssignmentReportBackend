<?php
/**
 * Created by PhpStorm.
 * User: fadli
 * Date: 06/02/2019
 * Time: 11:33
 */

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user) {
        return [
            'id'            => $user->id,
            'username'      => $user->username,
            'fullname'      => $user->full_name,
            'email'         => $user->email,
            'registered'    => $user->created_at->diffForHumans(),
        ];
    }
}
