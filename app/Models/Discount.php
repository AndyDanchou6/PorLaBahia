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
        'value',
        'expiration_date',
        'usage_limit',
        'minimum_payable',
        'maximum_payable',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'minimum_payable' => 'decimal:2',
        'maximum_payable' => 'decimal:2',
        'usage_limit' => 'integer',
        'value' => 'decimal:2',
        'expiration_date' => 'date',
    ];

    public function booking()
    {
        return $this->hasMany(Reservation::class);
    }

    public function generateDiscountCode(int $length = 10): string
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';

        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[random_int(0, strlen($characters) - 1)];
        }

        while (self::where('discount_code', $code)->exists()) {
            $code = '';
            for ($i = 0; $i < $length; $i++) {
                $code .= $characters[random_int(0, strlen($characters) - 1)];
            }
        }

        return $code;
    }
}
