<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admin';
    protected $primaryKey = 'id_adm';
    public $timestamps = false;
    protected $fillable = [
        'kode_peg',
        'nama_adm',
        'region_adm',
        'id_user',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'id_user');
    }
}
