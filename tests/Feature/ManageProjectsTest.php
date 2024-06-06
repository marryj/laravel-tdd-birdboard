<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public static function setUpBeforeClass(): void
    {
        echo "setUpBeforeClass";
    }

    public function setUp(): void
    {
        echo "Setup";
        parent::setUp();
    }

    public function test_guest_cannot_manage_project()
    {
        $project = Project::factory()->create();

        $this->post('/projects', $project->toArray())->assertRedirect('login');
        $this->get('/projects/create')->assertRedirect('login');
        $this->get('/projects')->assertRedirect('login');
        $this->get($project->path())->assertRedirect('login');

        return $project;
    }

    /**
     * A basic feature test example.
     */
    public function test_a_user_can_create_a_project(): void
    {
        $this->signIn();

        $this->get(route('projects.create'))->assertStatus(200);

        $attributes = [
            "title" => $this->faker->title,
            "description" => $this->faker->paragraph,

        ];

        $response = $this->post('/projects', $attributes);
        
        $response->assertRedirect(Project::where($attributes)->first()->path());

        $this->assertDatabaseHas("projects", $attributes);

        $this->get('/projects')->assertSee($attributes['title']);
    }

    public function test_a_user_can_view_their_project()
    {
        $this->signIn();
        $this->withoutExceptionHandling();
        $project = Project::factory()->create(['owner_id' => auth()->id()]);

        $this
            ->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    public function test_an_authenticated_user_cannot_view_the_projects_of_others()
    {
        $this->signIn();
        $project = Project::factory()->create();

        $this->get($project->path())->assertStatus(403);
    }

    public function test_projects_require_a_title()
    {
        $this->signIn();

        $attributes = Project::factory()->raw(['title' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    public function test_projects_require_a_description()
    {
        $this->signIn();
        $attributes = Project::factory()->raw(['description' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }

    public function test_it_belongs_to_an_owner()
    {
        $project = Project::factory()->create();

        $this->assertInstanceOf(User::class, $project->owner);

    }

}
