<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{

 protected $fillable = [
        'content',          
        'notable_id',       
        'notable_type',     
    ];

  public function notable()
    {
        return $this->morphTo();
    }


     public function setContentAttribute($value)
    {
        $this->attributes['content'] = $value ? ucfirst(trim($value)) : '';
    }

}
