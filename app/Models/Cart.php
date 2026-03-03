<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    // Campos que se pueden asignar 
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity'
    ];

    // Relación con usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con producto
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
