<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Admin;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    protected $table = 'user';
    protected $primaryKey = 'id_user';
    public $timestamps = false;

    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'password',
        'us_role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'id_user');
    }

    public function dosen()
    {
        return $this->hasOne(Dosen::class, 'id_user');
    }

    public function admin()
    {
        return $this->hasOne(Admin::class, 'id_user');
    }
}
