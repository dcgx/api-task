<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Event;

it('can create a user', function () {
    $response = $this->post('/api/users', [
        'name' => 'Test',
        'email' => 'test@kiibo-task.test',
        'password' => 'password',
    ]);

    $response->assertStatus(201);
    $response->assertJson([
        'id' => $response['id'],
        'name' => 'Test',
        'email' => 'test@kiibo-task.test',
        'profile_photo_url' => $response['profile_photo_url'],
        'created_at' => $response['created_at'],
        'updated_at' => $response['updated_at'],
    ]);
});
