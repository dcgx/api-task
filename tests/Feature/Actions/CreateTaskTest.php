<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Event;


it('return unauthorized if user is not logged in', function () {
    $response = $this->get('/api/tasks');
    $response->assertStatus(401);
});

it('can create a task', function() {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/api/tasks', [
        'title' => 'Test task',
        'description' => 'Test description',
        'status' => 'pending',
        'user_id' => $user->id,
    ]);

    $response->assertStatus(201);
    $response->assertJson([
        'title' => 'Test task',
        'description' => 'Test description',
        'status' => 'pending',
        'user_id' => $user->id,
    ]);
});