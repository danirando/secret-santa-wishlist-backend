<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Wishlist extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function gifts()
    {
        return $this->hasMany(Gift::class);
    }
}
