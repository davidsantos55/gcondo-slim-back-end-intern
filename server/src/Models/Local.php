<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Local extends Model
 {
    public function reservas()
    {
    return $this->hasMany(Reservation::class);
    }
    protected $table = 'locais';
    protected $fillable = [
        'nome',
        'quantidade_maxima_pessoas', 
        'metros_quadrados'
    ];
}