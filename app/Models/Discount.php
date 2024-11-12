<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'discount_code',
        'description',
        'discount_type',
        'value',
        'expiration_date',
        'usage_limit',
        'minimum_order',
        'maximum_order',
        'stacking_restriction',
        'applicability',
    ];

        /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'minimum_order' => 'integer',
        'maximum_order' => 'integer',
        'value' => 'integer',
        'expiration_date' => 'datetime',
    ];
}
