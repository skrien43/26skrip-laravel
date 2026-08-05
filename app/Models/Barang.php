<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'brg_id';
    public $timestamps = false;
    protected $fillable = [
        'brg_kode',
        'brg_cat',
        'brg_name',
        'brg_region',
        'brg_status',
        'brg_in',
        'brg_updated'
    ];
}
