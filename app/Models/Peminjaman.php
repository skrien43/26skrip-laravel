<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';
    protected $primaryKey = 'brw_id';
    public $timestamps = false;
    protected $fillable = [
        'id_user',
        'brw_mkul',
        'brw_pengajar',
        'brw_kelas',
        'brw_room',
        'brw_jamkul',
        'no_hp',
        'brw_region',
        'brw_jaminan',
        'brw_otp',
        'brw_status',
    ];
}
