<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB; // Tambahkan ini untuk Query Database
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'level_id',       // Pastikan ini ada agar bisa disimpan
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relasi User ke Level
     * (Setiap user punya 1 level)
     */
    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

    /**
     * Fungsi Cek Permission (Jantung Utamanya)
     * Dipanggil oleh Helper nanti.
     */
    public function hasPermission($permissionName)
    {
        // Query cek ke tabel role_permissions dan permissions
        // Berdasarkan level_id milik user ini
        $exists = DB::table('role_permissions')
            ->join('permissions', 'role_permissions.permission_id', '=', 'permissions.id')
            ->where('role_permissions.level_id', $this->level_id)
            ->where('permissions.name', $permissionName)
            ->exists();

        return $exists;
    }
}