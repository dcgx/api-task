<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Event;

it('can update a user', function () {
    $user = User::factory()->create();

    $response = $this->put('/api/users/' . $user->id, [
        'name' => 'Test (Updated)',
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'id' => $user->id,
        'name' => 'Test (Updated)',
        'email' => $user->email,
        'email_verified_at' => $user->email_verified_at ? $user->email_verified_at->format('Y-m-d\TH:i:s.u\Z') : null,
        'current_team_id' => $user->current_team_id,
        'profile_photo_path' => $user->profile_photo_path,
        'profile_photo_url' => $response['profile_photo_url'],
        'deleted_at' => $user->deleted_at ? $user->deleted_at->format('Y-m-d\TH:i:s.u\Z') : null,
        'created_at' => $user->created_at->format('Y-m-d\TH:i:s.u\Z'),
        'updated_at' => $user->updated_at->format('Y-m-d\TH:i:s.u\Z'),
    ]);
});
