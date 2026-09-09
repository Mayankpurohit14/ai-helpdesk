<?php

use App\Models\User;
use App\Models\Category;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    foreach (['admin', 'agent', 'customer'] as $role) {
        Role::firstOrCreate(['name' => $role]);
    }
    Category::factory()->create(['name' => 'Technical']);
});

it('allows a customer to create a ticket', function () {
    $customer = User::factory()->create();
    $customer->assignRole('customer');

    $response = $this->actingAs($customer, 'sanctum')->postJson('/api/tickets', [
        'subject' => 'Payment failed',
        'description' => 'My payment failed twice while booking a venue.',
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('data.subject', 'Payment failed');

    $this->assertDatabaseHas('tickets', [
        'subject' => 'Payment failed',
        'customer_id' => $customer->id,
    ]);
});

it('prevents a customer from updating ticket status', function () {
    $customer = User::factory()->create();
    $customer->assignRole('customer');

    $ticket = \App\Models\Ticket::factory()->create(['customer_id' => $customer->id]);

    $response = $this->actingAs($customer, 'sanctum')
        ->putJson("/api/tickets/{$ticket->id}", ['status' => 'resolved']);

    $response->assertStatus(403);
});

it('allows an agent to update ticket status', function () {
    $agent = User::factory()->create();
    $agent->assignRole('agent');

    $ticket = \App\Models\Ticket::factory()->create();

    $response = $this->actingAs($agent, 'sanctum')
        ->putJson("/api/tickets/{$ticket->id}", ['status' => 'in_progress']);

    $response->assertStatus(200)
        ->assertJsonPath('data.status', 'in_progress');
});

it('scopes ticket listing to the customer who owns them', function () {
    $customerA = User::factory()->create();
    $customerA->assignRole('customer');
    $customerB = User::factory()->create();
    $customerB->assignRole('customer');

    \App\Models\Ticket::factory()->create(['customer_id' => $customerA->id]);
    \App\Models\Ticket::factory()->create(['customer_id' => $customerB->id]);

    $response = $this->actingAs($customerA, 'sanctum')->getJson('/api/tickets');

    $response->assertStatus(200)->assertJsonCount(1, 'data');
});
