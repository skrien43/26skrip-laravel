<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    protected $table = 'rooms';
    protected $primaryKey = 'room_id';
    public $timestamps = false;
    protected $guarded = [
        'room_name',
        'room_region',
        'room_gd',
        'room_floor',
        'room_ord'
    ];

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'room_id', 'room_id');
    }
}
