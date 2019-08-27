<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Komen extends Model
{
    protected $fillable = [
        'id', 'kode_iklan', 'id_member', 'komentar',
    ];

    protected $hidden = [
         
    ];

    public function iklan(){
    	return $this->belongsTo(Iklan::class);
    }
    
    public function user(){
    	return $this->belongsTo(User::class);
    }
}
