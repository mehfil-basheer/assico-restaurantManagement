<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\MenuItem;
use App\Models\Reservation;
use App\Models\Table;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
          // Seed roles and permissions
          $this->call(RolesAndPermissionsSeeder::class);

          // Create users
          $admin = User::create([
              'name' => 'Admin User',
              'email' => 'admin@restaurantM.com',
              'password' => bcrypt('password'),
          ]);
          $admin->assignRole('admin');
  
          $staff = User::create([
              'name' => 'Staff User',
              'email' => 'staff@restaurantM.com',
              'password' => bcrypt('password'),
          ]);
          $staff->assignRole('staff');
  
          $customer = User::create([
              'name' => 'Customer User',
              'email' => 'customer@restaurantM.com',
              'password' => bcrypt('password'),
          ]);
          $customer->assignRole('customer');



            // Create tables
        Table::create(['number' => 'T001', 'seating_capacity' => 4]);
        Table::create(['number' => 'T002', 'seating_capacity' => 2]);

        // Create menu items
        MenuItem::create(['name' => 'Biriyani', 'description' => 'Chicken Biriyani', 'price' => 100.00, 'is_available' => true]);
        MenuItem::create(['name' => 'mandi', 'description' => 'chicken mandi', 'price' => 150.00, 'is_available' => true]);

        // Create reservations
        Reservation::create(['user_id' => $customer->id, 'table_id' => 1, 'reservation_time' => now()->addDays(1)]);
    }
}
