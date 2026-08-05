<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $table = 'schedules';
    protected $primaryKey = 'sch_id';
    public $timestamps = false;
    protected $fillable = [
        'room_id',
        'sch_day',
        'sch_jamkul',
        'sch_dosen',
        'sch_mkul',
        'sch_kelas',
        'room_status',
        'updated_at'
    ];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'room_id', 'room_id');
    }
}
