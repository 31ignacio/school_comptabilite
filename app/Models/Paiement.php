<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $guarded = [''];

    public function user()
    {
        return $this->belongsTo(user::class, 'user_id');
    }

    public function inscription()
    {
        return $this->belongsTo(Inscription::class, 'inscription_id');
    }
}
