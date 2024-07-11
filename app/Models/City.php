<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $primaryKey = 'city_id';
    protected $fillable = ['city_id', 'name', 'ibge', 'state_id'];

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class); // State é o modelo para estados
    }
}
