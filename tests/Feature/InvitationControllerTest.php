<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class InvitationControllerTest extends TestCase
{
    
    
    public function test_send_invitation_feature()
    {
        // We want to create a sender user
        $sender = User::factory()->create();

        // We want to create a Invited user
        $invited = User::factory()->create();

        // We want to hit the send api
       
        $response = $this->post('/api/send', ['sender'=> $sender->id, 'invited'=> $invited->id]);
        // We want to assert that we got status 200
        $response->assertStatus(200);

        
    }
}
