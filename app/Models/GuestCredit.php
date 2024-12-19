<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestCredit extends Model
{
    use HasFactory;

    protected $table = 'guest_credits';

    protected $fillable = [
        'guest_id',
        'coupon',
        'amount',
        'is_redeemed',
        'date_redeemed',
        'expiration_date',
        'status',
    ];

    protected $casts = [
        'booking_ids' => 'array',
    ];

    public function guest()
    {
        return $this->belongsTo(GuestInfo::class);
    }

    public static function generateCoupon($bookingSuffix): string
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $reference = '';
        $length = 4;

        for ($i = 0; $i < $length; $i++) {
            $reference .= $characters[random_int(0, strlen($characters) - 1)];
        }

        $coupon = 'CRDTS-' . $bookingSuffix . $reference;

        while (self::where('coupon', $coupon)->exists()) {
            $reference = '';
            for ($i = 0; $i < $length; $i++) {
                $reference .= $characters[random_int(0, strlen($characters) - 1)];
            }

            $coupon = 'CRDTS-' . $bookingSuffix . $reference;
        }

        return $coupon;
    }
}
