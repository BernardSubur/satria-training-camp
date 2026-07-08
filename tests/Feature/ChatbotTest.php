<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

class ChatbotTest extends TestCase
{
    use RefreshDatabase;

    public function test_chatbot_can_reply_to_member()
    {
        $user = User::factory()->create(['role' => 'member']);
        
        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Halo! Ini respons bot.']
                            ]
                        ]
                    ]
                ]
            ], 200)
        ]);

        $response = $this->actingAs($user)->post('/chatbot', [
            'message' => 'Halo bot'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'reply' => 'Halo! Ini respons bot.'
        ]);
    }

    public function test_chatbot_returns_error_on_api_failure()
    {
        $user = User::factory()->create(['role' => 'member']);
        
        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([], 500)
        ]);

        $response = $this->actingAs($user)->post('/chatbot', [
            'message' => 'Halo bot'
        ]);

        $response->assertStatus(200);
        $this->assertStringContainsString('Maaf, Sobat STC sedang istirahat sejenak', $response->json('reply'));
    }

    public function test_unauthenticated_cannot_access_chatbot()
    {
        $response = $this->post('/chatbot', [
            'message' => 'Halo bot'
        ]);

        $response->assertStatus(302); // Redirect to login
    }
}
