<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name', 'email', 'phone', 'dob', 'registered_at',
        'is_active', 'balance', 'parent_id',
    ];

    public function parent()
    {
        return $this->belongsTo(Client::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Client::class, 'parent_id');
    }
}
