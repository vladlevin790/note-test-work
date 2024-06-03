<?php

namespace Tests\Feature;

use App\Models\Note;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_delete_note(): void
    {
        $note = Note::factory()->create();

        $response = $this->delete("/api/v1/notebook/$note->id",);
        $response->assertStatus(200);
    }
}
