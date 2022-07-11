<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\CartItem;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['id','content','key','user_id'];
    public $incrementing = false;

    public function items () {
        return $this->hasMany(CartItem::class, 'cart_id');
    }
}
