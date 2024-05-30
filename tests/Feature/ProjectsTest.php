<?php

namespace Tests\Feature;

use App\Models\Project;
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

    public function test_projects_require_a_title()
    {
        $attributes = Project::factory()->raw(['title' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    public function test_projects_require_a_description()
    {
        $attributes = Project::factory()->raw(['description' => '']);


        $this->post('/projects',$attributes)->assertSessionHasErrors('description');
    }
}
