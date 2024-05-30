<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_a_user_can_create_a_project(): void
    {
        $this->withoutExceptionHandling();

        $attributes = [
            "title" => $this->faker->title,
            "description" => $this->faker->paragraph,

        ];

        $this->post('/projects', $attributes)->assertRedirect('projects.index');

        $this->assertDatabaseHas("projects", $attributes);

        $this->get('/projects')->assertSee($attributes['title']);
    }
}
