<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Driver;
use App\Models\Property;
use App\Models\Transport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransportAndDriverModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_register_driver_and_assign_to_transport()
    {
        // 1. Create users
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@unicrib.test',
            'password' => bcrypt('password'),
            'category' => 'admin',
        ]);

        $student = User::create([
            'name' => 'John Student',
            'email' => 'student@unicrib.test',
            'password' => bcrypt('password'),
            'category' => 'student',
            'phone' => '0712345678',
        ]);

        $landlord = User::create([
            'name' => 'Jane Landlord',
            'email' => 'landlord@unicrib.test',
            'password' => bcrypt('password'),
            'category' => 'landlord',
            'phone' => '0798765432',
        ]);

        // 2. Create property & booking
        $property = Property::create([
            'landlord_id' => $landlord->id,
            'title' => 'Student Hostel Room',
            'description' => 'Near university gate.',
            'price' => 9000,
            'bedrooms' => 1,
            'bathrooms' => 1,
            'size' => 18,
            'address' => 'Kutus',
            'area' => 'Main Campus',
            'amenities' => ['wifi'],
        ]);

        $booking = Booking::create([
            'student_id' => $student->id,
            'landlord_id' => $landlord->id,
            'property_id' => $property->id,
            'status' => 'approved',
            'payment_status' => 'paid',
        ]);

        // 3. Act as admin and register a driver
        $this->actingAs($admin);

        $driverResponse = $this->post(route('admin.drivers.store'), [
            'name' => 'Driver Mike',
            'phone' => '0700112233',
            'vehicle_model' => 'Toyota Probox',
            'vehicle_plate' => 'KDA 123A',
        ]);

        $driverResponse->assertSessionHasNoErrors();
        $this->assertDatabaseHas('drivers', [
            'name' => 'Driver Mike',
            'vehicle_plate' => 'KDA 123A',
        ]);

        $driver = Driver::where('vehicle_plate', 'KDA 123A')->first();

        // 4. Create a pending transport request for the student
        $transport = Transport::create([
            'booking_id' => $booking->id,
            'property_id' => $property->id,
            'student_id' => $student->id,
            'student_name' => $student->name,
            'student_phone' => $student->phone,
            'requested_date' => now()->addDay()->toDateString(),
            'assigned_at' => now()->addDay()->setTime(10, 0),
            'status' => 'pending',
        ]);

        // 5. Assign driver to transport request (Dispatch ride)
        $dispatchResponse = $this->post(route('admin.transports.assign-driver', $transport->id), [
            'driver_id' => $driver->id,
        ]);

        $dispatchResponse->assertRedirect();
        $dispatchResponse->assertSessionHas('success');

        // 6. Assert database state updated to driver_assigned
        $this->assertDatabaseHas('transports', [
            'id' => $transport->id,
            'driver_id' => $driver->id,
            'status' => 'driver_assigned',
        ]);

        $this->assertDatabaseHas('drivers', [
            'id' => $driver->id,
            'status' => 'busy',
        ]);
    }
}