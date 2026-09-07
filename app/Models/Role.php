<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
     protected $table = 'roles';
     
    protected $fillable = [
        'nama_role',
    ];

    /**
     * Relasi many-to-many ke User lewat tabel pivot user_roles.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_roles');
    }
}