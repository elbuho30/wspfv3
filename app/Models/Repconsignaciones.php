<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repconsignaciones extends Model
{
    use HasFactory;
    protected $table = 'repconsignaciones';
    protected $guarded = ['id'];
    protected $casts = [
        
    ];
    protected $hidden = [
        'created_at','updated_at'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
