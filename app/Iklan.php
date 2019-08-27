<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Iklan extends Model
{
    protected $fillable = [
        'judul', 'deskripsi', 'harga', 'kategori', 'ket', 'verifikasi',
    ];

    protected $hidden = [
        'id', 
    ];

    public function user(){
    	return $this->belongsTo(User::class);
    }

    public function komen(){
    	return $this->hasMany(Komen::class);
    }

}
