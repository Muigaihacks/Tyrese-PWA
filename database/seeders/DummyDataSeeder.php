<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\CasualLabourer;
use App\Models\Hub;
use App\Models\LeasedUnit;
use App\Models\Inventory;
use App\Models\Battery;
use App\Models\Visit;
use App\Models\CasualLabourerAttendance;
use App\Models\InventoryAction;
use App\Models\CrateMovement;
use App\Models\ColdStorageUnit;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds for demo purposes with dummy data.
     */
    public function run(): void
    {
        if ($this->command) {
            $this->command->info('Creating dummy data for demo purposes...');
        }

        // Create Roles first
        $this->createRoles();
        
        // Create Dummy Users
        $users = $this->createUsers();
        
        // Create Dummy Hubs
        $hubs = $this->createHubs();
        
        // Create Dummy Cold Storage Units
        $units = $this->createColdStorageUnits($hubs);
        
        // Create Dummy Inventory
        $inventoryItems = $this->createInventory();
        
        // Create Dummy Batteries
        $batteries = $this->createBatteries($units);
        
        // Create Dummy Visits
        $this->createVisits($units, $users);
        
        // Create Dummy Casual Labourers
        $labourers = $this->createCasualLabourers($users);
        
        // Create Dummy Attendance Records
        $this->createAttendanceRecords($labourers);
        
        // Create Dummy Inventory Actions
        $this->createInventoryActions($inventoryItems, $users);
        
        // Create Dummy Crate Movements
        $this->createCrateMovements($hubs, $units);

        if ($this->command) {
            $this->command->info('Dummy data created successfully!');
            $this->command->info('');
            $this->command->info('Demo Login Credentials:');
            $this->command->info('Admin - Email: admin@demo.com, Password: demo123');
            $this->command->info('Manager - Email: manager@demo.com, Password: demo123');
            $this->command->info('Technician - Email: tech@demo.com, Password: demo123');
        }
    }

    private function createRoles()
    {
        $roles = ['admin', 'manager', 'technician', 'viewer', 'labourer'];
        
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }
    }

    private function createUsers()
    {
        $users = [];

        // Admin User
        $admin = User::create([
            'name' => 'Demo Admin',
            'email' => 'admin@demo.com',
            'password' => Hash::make('demo123'),
            'role' => 'admin',
            'status' => true, // boolean: true = active, false = inactive
        ]);
        $admin->assignRole('admin');
        $users[] = $admin;

        // Manager Users
        $managers = [
            ['name' => 'Jane Smith', 'email' => 'manager@demo.com'],
            ['name' => 'Robert Johnson', 'email' => 'manager2@demo.com'],
        ];

        foreach ($managers as $managerData) {
            $manager = User::create([
                'name' => $managerData['name'],
                'email' => $managerData['email'],
                'password' => Hash::make('demo123'),
                'role' => 'manager',
                'status' => true, // boolean: true = active
            ]);
            $manager->assignRole('manager');
            $users[] = $manager;
        }

        // Technician Users
        $technicians = [
            ['name' => 'Mike Wilson', 'email' => 'tech@demo.com'],
            ['name' => 'Sarah Brown', 'email' => 'tech2@demo.com'],
            ['name' => 'David Lee', 'email' => 'tech3@demo.com'],
        ];

        foreach ($technicians as $techData) {
            $tech = User::create([
                'name' => $techData['name'],
                'email' => $techData['email'],
                'password' => Hash::make('demo123'),
                'role' => 'technician',
                'status' => true, // boolean: true = active
            ]);
            $tech->assignRole('technician');
            $users[] = $tech;
        }

        if ($this->command) {
            $this->command->info('✓ Created dummy users');
        }
        return $users;
    }

    private function createHubs()
    {
        $hubs = [
            ['name' => 'North Hub', 'location' => 'North Region', 'is_kibiku' => false],
            ['name' => 'South Hub', 'location' => 'South Region', 'is_kibiku' => false],
            ['name' => 'East Hub', 'location' => 'East Region', 'is_kibiku' => false],
            ['name' => 'West Hub', 'location' => 'West Region', 'is_kibiku' => false],
            ['name' => 'Central Hub', 'location' => 'Central Region', 'is_kibiku' => true],
            ['name' => 'Main Storage', 'location' => 'Headquarters', 'is_kibiku' => false],
        ];

        $createdHubs = [];
        foreach ($hubs as $hubData) {
            $hub = Hub::create([
                'name' => $hubData['name'],
                'location' => $hubData['location'],
                'crate_count' => rand(50, 200),
                'scale_count' => rand(5, 15),
                'is_kibiku' => $hubData['is_kibiku'],
            ]);
            $createdHubs[] = $hub;
        }

        if ($this->command) {
            $this->command->info('✓ Created dummy hubs');
        }
        return $createdHubs;
    }

    private function createColdStorageUnits($hubs)
    {
        $units = [];
        $unitNames = ['Alpha Unit', 'Beta Unit', 'Gamma Unit', 'Delta Unit', 'Epsilon Unit', 'Zeta Unit', 'Eta Unit', 'Theta Unit'];

        foreach ($unitNames as $index => $unitName) {
            $hub = $hubs[array_rand($hubs)];
            
            $unit = LeasedUnit::create([
                'name' => $unitName,
                'address' => $hub->location . ' - Building ' . ($index + 1),
                'latitude' => round(-1.286389 + (rand(-1000, 1000) / 10000), 7),
                'longitude' => round(36.817223 + (rand(-1000, 1000) / 10000), 7),
                'lessee_name' => 'Demo Storage Co. Ltd.',
                'lessee_contact' => '0700' . str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT),
                'notes' => 'Capacity: ' . rand(500, 2000) . ' units',
            ]);
            $units[] = $unit;
        }

        if ($this->command) {
            $this->command->info('✓ Created dummy cold storage units');
        }
        return $units;
    }

    private function createInventory()
    {
        $items = [
            // Tools
            ['product' => 'Adjustable Wrench 12"', 'item_type' => 'tool', 'quantity' => 8, 'condition' => 'excellent', 'stock_level' => 'high'],
            ['product' => 'Screwdriver Set (6pc)', 'item_type' => 'tool', 'quantity' => 5, 'condition' => 'good', 'stock_level' => 'medium'],
            ['product' => 'Power Drill 18V', 'item_type' => 'tool', 'quantity' => 3, 'condition' => 'excellent', 'stock_level' => 'medium'],
            ['product' => 'Socket Set 24pc', 'item_type' => 'tool', 'quantity' => 4, 'condition' => 'good', 'stock_level' => 'medium'],
            ['product' => 'Digital Multimeter', 'item_type' => 'tool', 'quantity' => 6, 'condition' => 'excellent', 'stock_level' => 'high'],
            ['product' => 'Pipe Wrench 14"', 'item_type' => 'tool', 'quantity' => 2, 'condition' => 'fair', 'stock_level' => 'low'],
            ['product' => 'Wire Stripper', 'item_type' => 'tool', 'quantity' => 7, 'condition' => 'good', 'stock_level' => 'high'],
            
            // Spare Parts
            ['product' => 'Compressor Motor Belt', 'item_type' => 'spare_part', 'quantity' => 12, 'condition' => 'excellent', 'stock_level' => 'high'],
            ['product' => 'Temperature Sensor Module', 'item_type' => 'spare_part', 'quantity' => 15, 'condition' => 'excellent', 'stock_level' => 'high'],
            ['product' => 'Evaporator Fan Motor', 'item_type' => 'spare_part', 'quantity' => 6, 'condition' => 'good', 'stock_level' => 'medium'],
            ['product' => 'Door Seal Gasket', 'item_type' => 'spare_part', 'quantity' => 8, 'condition' => 'excellent', 'stock_level' => 'high'],
            ['product' => 'Pressure Switch', 'item_type' => 'spare_part', 'quantity' => 10, 'condition' => 'good', 'stock_level' => 'high'],
            ['product' => 'Refrigerant R404A (1kg)', 'item_type' => 'spare_part', 'quantity' => 4, 'condition' => 'excellent', 'stock_level' => 'medium'],
            ['product' => 'Control Panel Circuit Board', 'item_type' => 'spare_part', 'quantity' => 3, 'condition' => 'excellent', 'stock_level' => 'low'],
            ['product' => 'Condenser Coil', 'item_type' => 'spare_part', 'quantity' => 2, 'condition' => 'good', 'stock_level' => 'low'],
            
            // Assets
            ['product' => 'Industrial Thermometer', 'item_type' => 'asset', 'quantity' => 5, 'condition' => 'excellent', 'stock_level' => 'medium', 'notes' => 'Calibrated annually'],
            ['product' => 'Portable Generator 5KW', 'item_type' => 'asset', 'quantity' => 2, 'condition' => 'good', 'stock_level' => 'low', 'notes' => 'For emergency backup'],
            ['product' => 'Safety Equipment Kit', 'item_type' => 'asset', 'quantity' => 10, 'condition' => 'excellent', 'stock_level' => 'high', 'notes' => 'Includes PPE'],
        ];

        $createdItems = [];
        foreach ($items as $itemData) {
            $item = Inventory::create([
                'product' => $itemData['product'],
                'item_type' => $itemData['item_type'],
                'quantity' => $itemData['quantity'],
                'condition' => $itemData['condition'],
                'stock_level' => $itemData['stock_level'],
                'date_added' => now()->subDays(rand(1, 90)),
                'notes' => $itemData['notes'] ?? null,
            ]);
            $createdItems[] = $item;
        }

        if ($this->command) {
            $this->command->info('✓ Created dummy inventory items');
        }
        return $createdItems;
    }

    private function createBatteries($units)
    {
        $batteries = [];
        $conditions = ['excellent', 'good', 'fair', 'poor'];
        $statuses = ['active', 'inactive', 'maintenance'];

        foreach ($units as $index => $unit) {
            if ($index % 2 == 0) { // Create batteries for every other unit
                $battery = Battery::create([
                    'unique_code' => 'BAT-' . strtoupper(substr(md5($unit->id . time() . $index), 0, 8)),
                    'cold_storage_unit_id' => $unit->id,
                    'condition' => $conditions[array_rand($conditions)],
                    'status' => $statuses[array_rand($statuses)],
                    'notes' => ['Regular maintenance required', 'Monitor battery health', 'Good condition', 'Check voltage levels'][rand(0, 3)],
                ]);
                $batteries[] = $battery;
            }
        }

        if ($this->command) {
            $this->command->info('✓ Created dummy batteries');
        }
        return $batteries;
    }

    private function createVisits($units, $users)
    {
        $statuses = ['upcoming', 'completed', 'cancelled', 'in_progress'];
        
        for ($i = 0; $i < 20; $i++) {
            Visit::create([
                'unit_id' => $units[array_rand($units)]->id,
                'scheduled_by' => $users[array_rand($users)]->id,
                'scheduled_for' => now()->addDays(rand(-30, 30)),
                'status' => $statuses[array_rand($statuses)],
                'notes' => ['Routine maintenance', 'Emergency repair', 'Monthly inspection', 'Temperature check', 'Equipment calibration'][rand(0, 4)],
                'location' => ['North Region', 'South Region', 'East Region', 'West Region'][rand(0, 3)],
            ]);
        }

        if ($this->command) {
            $this->command->info('✓ Created dummy visits');
        }
    }

    private function createCasualLabourers($users)
    {
        $firstNames = ['John', 'James', 'Michael', 'William', 'David', 'Mary', 'Patricia', 'Jennifer', 'Linda', 'Elizabeth'];
        $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez'];
        $labourers = [];

        for ($i = 0; $i < 15; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $name = $firstName . ' ' . $lastName;
            
            $labourer = CasualLabourer::create([
                'name' => $name,
                'gender' => ['M', 'F'][rand(0, 1)],
                'age_group' => ['18-35', '36+ YEARS'][rand(0, 1)],
                'phone_number' => '0' . rand(700, 799) . rand(100000, 999999),
                'id_number' => rand(10000000, 99999999),
                'next_of_kin_name' => $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)],
                'next_of_kin_phone' => '0' . rand(700, 799) . rand(100000, 999999),
                'health_declaration' => true,
                'skills_confirmation' => true,
                'ppe_provided' => true,
                'safety_briefing' => true,
                'tool_safety_agreement' => true,
                'accident_cover_enrolled' => true,
                'data_consent' => true,
                'status' => ['active', 'active', 'active', 'inactive'][rand(0, 3)], // Mostly active
                'contract_start_date' => now()->subMonths(rand(1, 12)),
                'contract_end_date' => rand(0, 1) ? now()->addMonths(rand(1, 6)) : null,
                'user_id' => null, // Could link to some users if needed
            ]);
            $labourers[] = $labourer;
        }

        if ($this->command) {
            $this->command->info('✓ Created dummy casual labourers');
        }
        return $labourers;
    }

    private function createAttendanceRecords($labourers)
    {
        // Create attendance for the last 30 days
        for ($day = 0; $day < 30; $day++) {
            $workDate = now()->subDays($day)->format('Y-m-d');
            
            // Random selection of labourers present each day
            $numPresent = rand(5, min(12, count($labourers)));
            $presentIndices = (array) array_rand($labourers, $numPresent);
            
            foreach ($presentIndices as $labourerIndex) {
                $labourer = $labourers[$labourerIndex];
                
                $timeIn = sprintf('%02d:%02d:00', rand(6, 8), rand(0, 59));
                $timeOut = sprintf('%02d:%02d:00', rand(15, 18), rand(0, 59));
                $hoursWorked = rand(7, 10);
                
                CasualLabourerAttendance::create([
                    'casual_labourer_id' => $labourer->id,
                    'work_date' => $workDate,
                    'time_in' => $timeIn,
                    'time_out' => $timeOut,
                    'job_description' => ['General Labor', 'Loading/Unloading', 'Maintenance Support', 'Cleaning'][rand(0, 3)],
                    'total_hours' => $hoursWorked * 60, // in minutes
                    'total_hours_decimal' => $hoursWorked + (rand(0, 1) * 0.5),
                    'notes' => rand(0, 5) == 0 ? 'Late arrival' : null,
                ]);
            }
        }

        if ($this->command) {
            $this->command->info('✓ Created dummy attendance records');
        }
    }

    private function createInventoryActions($inventoryItems, $users)
    {
        $actionTypes = ['checkout', 'return'];
        $conditions = ['excellent', 'good', 'fair', 'poor'];
        
        // First create some locations if they don't exist
        $locations = \App\Models\Location::all();
        if ($locations->isEmpty()) {
            $locations = collect([
                \App\Models\Location::create(['name' => 'Main Warehouse', 'description' => 'Primary storage facility']),
                \App\Models\Location::create(['name' => 'North Hub Storage', 'description' => 'Northern region storage']),
                \App\Models\Location::create(['name' => 'Service Van 1', 'description' => 'Mobile service vehicle']),
            ]);
        }
        
        for ($i = 0; $i < 30; $i++) {
            InventoryAction::create([
                'inventory_id' => $inventoryItems[array_rand($inventoryItems)]->id,
                'location_id' => $locations->random()->id,
                'user_id' => $users[array_rand($users)]->id,
                'action_type' => $actionTypes[array_rand($actionTypes)],
                'quantity' => rand(1, 5),
                'condition_before' => $conditions[array_rand($conditions)],
                'condition_after' => $conditions[array_rand($conditions)],
                'notes' => ['Routine check', 'Emergency use', 'Scheduled maintenance', 'Equipment transfer', 'Repair completed'][rand(0, 4)],
                'created_at' => now()->subDays(rand(0, 60)),
            ]);
        }

        if ($this->command) {
            $this->command->info('✓ Created dummy inventory actions');
        }
    }

    private function createCrateMovements($hubs, $units)
    {
        // Get users for the movements
        $users = \App\Models\User::all();
        
        for ($i = 0; $i < 40; $i++) {
            $fromHub = $hubs[array_rand($hubs)];
            $toHub = $hubs[array_rand($hubs)];
            
            // Ensure from and to are different
            while ($fromHub->id === $toHub->id) {
                $toHub = $hubs[array_rand($hubs)];
            }
            
            CrateMovement::create([
                'from_hub_id' => $fromHub->id,
                'to_hub_id' => $toHub->id,
                'crate_count' => rand(10, 50),
                'scale_count' => rand(1, 5),
                'user_id' => $users->random()->id,
                'notes' => ['Stock replenishment', 'Seasonal transfer', 'Emergency supply', 'Regular rotation'][rand(0, 3)],
                'created_at' => now()->subDays(rand(0, 60)),
            ]);
        }

        if ($this->command) {
            $this->command->info('✓ Created dummy crate movements');
        }
    }
}

