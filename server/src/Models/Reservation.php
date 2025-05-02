<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Unit;


class Reservation extends Model
{
    protected $table = 'reservas';

    protected $fillable = [
        'nome',
        'unidade_id',
        'quantidade_pessoas',
        'data',
        'local_id'
    ];

    public function local()
    {
        return $this->belongsTo(Local::class);
    }

    public function unity(){
        return $this->belongsTo(Unit::class,'unidade_id');
    }
}
