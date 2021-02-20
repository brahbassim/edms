<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($user){
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'phone' => $user->phone,
                    'status' => $user->status,
                    'status_print' => status($user->status),
                    'avatar' => $user->avatar,
                    'permissions' => $user->permissions->pluck('id'),
                    'roles' => $user->roles->pluck('id')
                ];
            })
        ];
    }
}
