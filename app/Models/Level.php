<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $table = 'levels';
    protected $guarded = [];

    // Relasi ke User
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Relasi ke Permission (Many to Many)
    // Ini jembatan ke tabel role_permissions
    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class, 
            'role_permissions', // Nama tabel pivot kamu
            'level_id', 
            'permission_id'
        );
    }
}