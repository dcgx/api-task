<?php

use App\Models\User;
use Illuminate\Support\Facades\Event;

test('it list tasks without filters', function () {
    Event::fake();

    User::factory()->count(5)->create();
    
    $response = $this->get('/api/users');

    $response->assertStatus(200);
    $response->assertJsonCount(5);
});