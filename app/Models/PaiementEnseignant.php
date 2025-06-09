<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaiementEnseignant extends Model
{
    use HasFactory;

     protected $guarded = [''];

    public function user()
    {
        return $this->belongsTo(user::class, 'user_id');
    }

    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class, 'enseignant_id');
    }
}
