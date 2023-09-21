<?php

use App\Models\Task;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;


test('it return unauthorized if user is not logged in', function () {
    $response = $this->get('/api/tasks');
    $response->assertStatus(401);
});

test('it list tasks without filters', function () {
    $user = User::factory()->create();
    
    Event::fake();

    Task::factory()->count(5)->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get('/api/tasks');

    $response->assertStatus(200);
    $response->assertJsonCount(5);
});

test('it list tasks with status filter', function () {
    $user = User::factory()->create();
    
    Event::fake();

    Task::factory()->count(2)->create(['user_id' => $user->id, 'status' => false]);
    Task::factory()->count(3)->create(['user_id' => $user->id, 'status' => true]);

    $response = $this->actingAs($user)->get('/api/tasks?status=true');
    $response->assertStatus(200);
    $response->assertJsonCount(3);

    $response = $this->actingAs($user)->get('/api/tasks?status=false');
    $response->assertStatus(200);
    $response->assertJsonCount(2);
});

test('it list tasks with title filter', function () {
    $user = User::factory()->create();
    
    Event::fake();

    Task::factory()->count(2)->create(['user_id' => $user->id, 'title' => 'Task 1']);
    Task::factory()->count(3)->create(['user_id' => $user->id, 'title' => 'Task 2']);

    $response = $this->actingAs($user)->get('/api/tasks?title=Task 1');
    $response->assertStatus(200);
    $response->assertJsonCount(2);

    $response = $this->actingAs($user)->get('/api/tasks?title=Task 2');
    $response->assertStatus(200);
    $response->assertJsonCount(3);
});

test('it list tasks with description filter', function () {
    $user = User::factory()->create();
    
    Event::fake();

    Task::factory()->count(2)->create(['user_id' => $user->id, 'description' => 'Description of Task 1']);
    Task::factory()->count(3)->create(['user_id' => $user->id, 'description' => 'Description Task 2']);

    $response = $this->actingAs($user)->get('/api/tasks?description=Description of Task 1');
    $response->assertStatus(200);
    $response->assertJsonCount(2);

    $response = $this->actingAs($user)->get('/api/tasks?description=Description Task 2');
    $response->assertStatus(200);
    $response->assertJsonCount(3);
});

test('it list tasks with multiple filters', function () {
    $user = User::factory()->create();
    
    Event::fake();

    Task::factory()->count(2)->create(['user_id' => $user->id, 'description' => 'Description of Task 1', 'status' => false]);
    Task::factory()->count(3)->create(['user_id' => $user->id, 'description' => 'Description Task 2', 'status' => true]);

    $response = $this->actingAs($user)->get('/api/tasks?description=Description of Task 1&status=false');
    $response->assertStatus(200);
    $response->assertJsonCount(2);

    $response = $this->actingAs($user)->get('/api/tasks?description=Description Task 2&status=true');
    $response->assertStatus(200);
    $response->assertJsonCount(3);
});