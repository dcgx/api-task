<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Event;

it('return unauthorized if user is not logged in', function () {
    $response = $this->get('/api/tasks');
    $response->assertStatus(401);
});

it('can update a task', function () {
    $user = User::factory()->create();
    $task = $this->actingAs($user)->post('/api/tasks', [
        'title' => 'Test task',
        'description' => 'Test description',
        'status' => true,
        'user_id' => $user->id,
    ])->json();

    $response = $this->actingAs($user)->put('/api/tasks/' . $task['id'], [
        'title' => 'New Test task',
        'description' => 'Test description (Updated)',
        'status' => false,
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'title' => 'New Test task',
        'description' => 'Test description (Updated)',
        'status' => false,
    ]);
});
