<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model

{
    use HasFactory;

    protected $primaryKey = 'shopify_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'no_of_pages',
        'author',
        'wholesale_price',
        'shopify_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function wholesalePrice(): Attribute
    {
        return new Attribute(
            fn($value, $attributes) => $value / 100,
            fn($value) => ['wholesale_price' => $value * 100]
        );

    }
}
