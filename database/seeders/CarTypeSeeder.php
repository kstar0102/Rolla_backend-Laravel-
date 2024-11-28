<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('car_type')->insert([
            [ 'car_type' => 'Acura', 'logo_path' => 'https://www.carlogos.org/car-logos/acura-logo.png' ],
            [ 'car_type' => 'Alfa Romeo', 'logo_path' => 'https://www.carlogos.org/car-logos/alfa-romeo-logo.png' ],
            [ 'car_type' => 'Aston Martin', 'logo_path' => 'https://www.carlogos.org/car-logos/aston-martin-logo.png' ],
            [ 'car_type' => 'Audi', 'logo_path' => 'https://www.carlogos.org/car-logos/audi-logo.png' ],
            [ 'car_type' => 'Bentley', 'logo_path' => 'https://www.carlogos.org/car-logos/bentley-logo.png' ],
            [ 'car_type' => 'BMW', 'logo_path' => 'https://www.carlogos.org/car-logos/bmw-logo.png' ],
            [ 'car_type' => 'Brabus', 'logo_path' => 'https://www.carlogos.org/car-logos/brabus-logo.png' ],
            [ 'car_type' => 'Bugatti', 'logo_path' => 'https://www.carlogos.org/car-logos/bugatti-logo.png' ],
            [ 'car_type' => 'Buick', 'logo_path' => 'https://www.carlogos.org/car-logos/buick-logo.png' ],
            [ 'car_type' => 'Cadillac', 'logo_path' => 'https://www.carlogos.org/car-logos/cadillac-logo.png' ],
            [ 'car_type' => 'Chevrolet', 'logo_path' => 'https://www.carlogos.org/car-logos/chevrolet-logo.png' ],
            [ 'car_type' => 'Chevrolet Corvette', 'logo_path' => 'https://www.carlogos.org/car-logos/corvette-logo.png' ],
            [ 'car_type' => 'Chrysler', 'logo_path' => 'https://www.carlogos.org/car-logos/chrysler-logo.png' ],
            [ 'car_type' => 'Dodge', 'logo_path' => 'https://www.carlogos.org/car-logos/dodge-logo.png' ],
            [ 'car_type' => 'Ferrari', 'logo_path' => 'https://www.carlogos.org/car-logos/ferrari-logo.png' ],
            [ 'car_type' => 'Fiat', 'logo_path' => 'https://www.carlogos.org/car-logos/fiat-logo.png' ],
            [ 'car_type' => 'Fisker', 'logo_path' => 'https://www.carlogos.org/car-logos/fisker-logo.png' ],
            [ 'car_type' => 'Ford', 'logo_path' => 'https://www.carlogos.org/car-logos/ford-logo.png' ],
            [ 'car_type' => 'Ford Mustang', 'logo_path' => 'https://www.carlogos.org/car-logos/ford-mustang-logo.png' ],
            [ 'car_type' => 'Genesis', 'logo_path' => 'https://www.carlogos.org/car-logos/genesis-logo.png' ],
            [ 'car_type' => 'GMC', 'logo_path' => 'https://www.carlogos.org/car-logos/gmc-logo.png' ],
            [ 'car_type' => 'Hennessey', 'logo_path' => 'https://www.carlogos.org/car-logos/hennessey-logo.png' ],
            [ 'car_type' => 'Honda', 'logo_path' => 'https://www.carlogos.org/car-logos/honda-logo.png' ],
            [ 'car_type' => 'Hummer', 'logo_path' => 'https://www.carlogos.org/car-logos/hummer-logo.png' ],
            [ 'car_type' => 'Hyundai', 'logo_path' => 'https://www.carlogos.org/car-logos/hyundai-logo.png' ],
            [ 'car_type' => 'Infiniti', 'logo_path' => 'https://www.carlogos.org/car-logos/infiniti-logo.png' ],
            [ 'car_type' => 'Jaguar', 'logo_path' => 'https://www.carlogos.org/car-logos/jaguar-logo.png' ],
            [ 'car_type' => 'Jeep', 'logo_path' => 'https://www.carlogos.org/car-logos/jeep-logo.png' ],
            [ 'car_type' => 'Karma', 'logo_path' => 'https://www.carlogos.org/car-logos/karma-logo.png' ],
            [ 'car_type' => 'Kenworth', 'logo_path' => 'https://www.carlogos.org/car-logos/kenworth-logo.png' ],
            [ 'car_type' => 'Kia', 'logo_path' => 'https://www.carlogos.org/car-logos/kia-logo.png' ],
            [ 'car_type' => 'Koenigsegg', 'logo_path' => 'https://www.carlogos.org/car-logos/koenigsegg-logo.png' ],
            [ 'car_type' => 'Lamborghini', 'logo_path' => 'https://www.carlogos.org/car-logos/lamborghini-logo.png' ],
            [ 'car_type' => 'Land Rover', 'logo_path' => 'https://www.carlogos.org/car-logos/land-rover-logo.png' ],
            [ 'car_type' => 'Lexus', 'logo_path' => 'https://www.carlogos.org/car-logos/lexus-logo.png' ],
            [ 'car_type' => 'Lincoln', 'logo_path' => 'https://www.carlogos.org/car-logos/lincoln-logo.png' ],
            [ 'car_type' => 'Lotus', 'logo_path' => 'https://www.carlogos.org/car-logos/lotus-logo.png' ],
            [ 'car_type' => 'Lucid', 'logo_path' => 'https://www.carlogos.org/car-logos/lucid-logo.png' ],
            [ 'car_type' => 'Mack', 'logo_path' => 'https://www.carlogos.org/car-logos/mack-logo.png' ],
            [ 'car_type' => 'Maserati', 'logo_path' => 'https://www.carlogos.org/car-logos/maserati-logo.png' ],
            [ 'car_type' => 'Maybach', 'logo_path' => 'https://www.carlogos.org/car-logos/maybach-logo.png' ],
            [ 'car_type' => 'Mazda', 'logo_path' => 'https://www.carlogos.org/car-logos/mazda-logo.png' ],
            [ 'car_type' => 'Mercedes-AMG', 'logo_path' => 'https://www.carlogos.org/car-logos/mercedes-amg-logo.png' ],
            [ 'car_type' => 'Mercedes-Benz', 'logo_path' => 'https://www.carlogos.org/car-logos/mercedes-benz-logo.png' ],
            [ 'car_type' => 'Mercury', 'logo_path' => 'https://www.carlogos.org/car-logos/mercury-logo.png' ],
            [ 'car_type' => 'Mini', 'logo_path' => 'https://www.carlogos.org/car-logos/mini-logo.png' ],
            [ 'car_type' => 'Nissan', 'logo_path' => 'https://www.carlogos.org/car-logos/nissan-logo.png' ],
            [ 'car_type' => 'Oldsmobile', 'logo_path' => 'https://www.carlogos.org/car-logos/oldsmobile-logo.png' ],
            [ 'car_type' => 'Pagani', 'logo_path' => 'https://www.carlogos.org/car-logos/pagani-logo.png' ],
            [ 'car_type' => 'Peugeot', 'logo_path' => 'https://www.carlogos.org/car-logos/peugeot-logo.png' ],
            [ 'car_type' => 'Pontiac', 'logo_path' => 'https://www.carlogos.org/car-logos/pontiac-logo.png' ],
            [ 'car_type' => 'Porsche', 'logo_path' => 'https://www.carlogos.org/car-logos/porsche-logo.png' ],
            [ 'car_type' => 'Ram', 'logo_path' => 'https://www.carlogos.org/car-logos/ram-logo.png' ],
            [ 'car_type' => 'Rivian', 'logo_path' => 'https://www.carlogos.org/car-logos/rivian-logo.png' ],
            [ 'car_type' => 'Rolls-Royce', 'logo_path' => 'https://www.carlogos.org/car-logos/rolls-royce-logo.png' ],
            [ 'car_type' => 'Saab', 'logo_path' => 'https://www.carlogos.org/car-logos/saab-logo.png' ],
            [ 'car_type' => 'Saturn', 'logo_path' => 'https://www.carlogos.org/car-logos/saturn-logo.png' ],
            [ 'car_type' => 'Scion', 'logo_path' => 'https://www.carlogos.org/car-logos/scion-logo.png' ],
            [ 'car_type' => 'Subaru', 'logo_path' => 'https://www.carlogos.org/car-logos/subaru-logo.png' ],
            [ 'car_type' => 'Suzuki', 'logo_path' => 'https://www.carlogos.org/car-logos/suzuki-logo.png' ],
            [ 'car_type' => 'Tesla', 'logo_path' => 'https://www.carlogos.org/car-logos/tesla-logo.png' ],
            [ 'car_type' => 'Toyota', 'logo_path' => 'https://www.carlogos.org/car-logos/toyota-logo.png' ],
        ]);
    }
}
