<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Accommodation;
use App\Models\GuestInfo;

class starterPackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $accommodationPhoto = "background.jpg";

        Accommodation::create([
            'room_name' => "Adrians House",
            'description' => 'meow meow',
            'free_pax' => 10,
            'excess_pax_price' => 10,
            'weekday_price' => 5000,
            'weekend_price' => 5500,
            'booking_fee' => 1500,
            'main_image' => $accommodationPhoto
        ]);

        GuestInfo::create([
            'first_name' => 'Aeri',
            'last_name' => 'Uchinaga',
            'contact_no' => '09698633244',
            'email' => 'aeriuchinaga@gmail.com',
            'address' => 'Tokyo, Japan',
            'fb_name' => 'Aeri Uchinaga',
        ]);
    }
}
