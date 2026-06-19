<?php

namespace Database\Seeders;

use App\Models\{User, Car, Promo};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@seraya.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'is_active' => true,
        ]);

        // Customer
        User::create([
            'name' => 'Customer',
            'email' => 'customer@seraya.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '081234567891',
            'is_active' => true,
        ]);

        // Cars
        $cars = [
            ['name' => 'Toyota Avanza', 'brand' => 'Toyota', 'model' => 'Avanza', 'year' => 2023, 'plate_number' => 'B 1234 ABC', 'color' => 'Putih', 'capacity' => 7, 'transmission' => 'manual', 'fuel_type' => 'petrol', 'price_per_day' => 350000, 'price_with_driver' => 550000, 'status' => 'available', 'description' => 'MPV nyaman untuk keluarga', 'features' => ['AC', 'Audio System', 'USB Charger']],
            ['name' => 'Toyota Innova', 'brand' => 'Toyota', 'model' => 'Innova', 'year' => 2023, 'plate_number' => 'B 5678 DEF', 'color' => 'Hitam', 'capacity' => 7, 'transmission' => 'automatic', 'fuel_type' => 'diesel', 'price_per_day' => 550000, 'price_with_driver' => 800000, 'status' => 'available', 'description' => 'MPV premium untuk perjalanan bisnis', 'features' => ['AC', 'Audio System', 'USB Charger', 'Captain Seat']],
            ['name' => 'Honda Brio', 'brand' => 'Honda', 'model' => 'Brio', 'year' => 2023, 'plate_number' => 'B 9012 GHI', 'color' => 'Merah', 'capacity' => 5, 'transmission' => 'automatic', 'fuel_type' => 'petrol', 'price_per_day' => 250000, 'price_with_driver' => 450000, 'status' => 'available', 'description' => 'City car compact dan irit', 'features' => ['AC', 'Audio System']],
            ['name' => 'Suzuki Ertiga', 'brand' => 'Suzuki', 'model' => 'Ertiga', 'year' => 2023, 'plate_number' => 'B 3456 JKL', 'color' => 'Silver', 'capacity' => 7, 'transmission' => 'manual', 'fuel_type' => 'petrol', 'price_per_day' => 300000, 'price_with_driver' => 500000, 'status' => 'available', 'description' => 'MPV irit dan nyaman', 'features' => ['AC', 'Audio System', 'USB Charger']],
            ['name' => 'Toyota Fortuner', 'brand' => 'Toyota', 'model' => 'Fortuner', 'year' => 2023, 'plate_number' => 'B 7890 MNO', 'color' => 'Putih', 'capacity' => 7, 'transmission' => 'automatic', 'fuel_type' => 'diesel', 'price_per_day' => 800000, 'price_with_driver' => 1100000, 'status' => 'available', 'description' => 'SUV premium untuk perjalanan mewah', 'features' => ['AC', 'Audio System', 'USB Charger', 'Captain Seat', 'Sunroof']],
            ['name' => 'Honda CR-V', 'brand' => 'Honda', 'model' => 'CR-V', 'year' => 2023, 'plate_number' => 'B 2345 PQR', 'color' => 'Hitam', 'capacity' => 5, 'transmission' => 'automatic', 'fuel_type' => 'petrol', 'price_per_day' => 700000, 'price_with_driver' => 950000, 'status' => 'available', 'description' => 'SUV modern dan elegan', 'features' => ['AC', 'Audio System', 'USB Charger', 'Sunroof']],
        ];

        foreach ($cars as $car) {
            Car::create($car);
        }

        // Promos
        Promo::create([
            'code' => 'DISKON10',
            'name' => 'Diskon 10%',
            'description' => 'Diskon 10% untuk semua mobil',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'min_booking_amount' => 500000,
            'valid_from' => now(),
            'valid_until' => now()->addMonth(),
            'is_active' => true,
        ]);

        Promo::create([
            'code' => 'HEMAT50K',
            'name' => 'Hemat Rp 50.000',
            'description' => 'Potongan Rp 50.000 untuk booking minimal Rp 300.000',
            'discount_type' => 'fixed',
            'discount_value' => 50000,
            'min_booking_amount' => 300000,
            'valid_from' => now(),
            'valid_until' => now()->addMonth(),
            'is_active' => true,
        ]);
    }
}
