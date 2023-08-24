<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    use HasFactory;
    protected $table = 'departamentos';
    protected $guarded = ['id'];
    protected $casts = [
        'estado' => 'boolean',
    ];

    public function pais()
    {
        return $this->belongsTo(Pais::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
