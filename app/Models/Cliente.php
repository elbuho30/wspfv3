<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    protected $table = 'clientes';
    protected $guarded = ['id'];
    protected $casts = [
        'estado' => 'boolean',
    ];
    protected $hidden = [
        'created_at','updated_at'
    ];

    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
