<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    protected $fillable = [
        'number', 'description','date_decret','date_decoration'
    ];

    public function members()
    {
        return $this->hasMany(Member::class);
    }
}
