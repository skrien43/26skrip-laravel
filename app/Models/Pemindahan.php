<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemindahan extends Model
{
    protected $table = 'pemindahan';
    protected $primaryKey = 'mv_id';
    public $timestamps = false;
    protected $fillable = [
        'id_user',
        'room_id',
        'room_replace',
        'mv_mkul',
        'mv_kelas',
        'mv_jamkul',
        'mv_reason',
        'mv_tgl_mkul',
        'mv_status'
    ];
}
