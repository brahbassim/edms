<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];



    public function getFileAttribute()
    {
        return env('APP_URL').'/storage/'. $this->attributes['file'];
    }

    
    public function scopeWhereSearch($query, $search)
    {
        return $query->where('name', 'LIKE', '%'.$search.'%');
    }

   
}
