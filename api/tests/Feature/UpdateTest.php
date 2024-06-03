<?php

namespace Tests\Feature;

use App\Models\Note;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function update_post(): void
    {
        $note = Note::factory()->create();
        $noteUpdate = [
            "full_name" => "exampleName",
            "phone_number" => "12345678910",
            "email" => "exampleEmail@gmail.com",
            "date_birth" => "17.05.2000",
            "path_to_photo"=> null,
        ];

        $response = $this->post("/api/v1/notebook/$note->id/",$noteUpdate);
        $response->assertStatus(200);
    }
}
