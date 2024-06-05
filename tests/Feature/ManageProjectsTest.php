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

    public function setUp(): void
    {
        echo "Setup";
        parent::setUp();
    }

    public static function setUpBeforeClass(): void
    {
        echo "setUpBeforeClass";
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
        $this->withoutExceptionHandling();

        $this->actingAs(User::factory()->create());

        $this->get(route('projects.create'))->assertStatus(200);

        $attributes = [
            "title" => $this->faker->title,
            "description" => $this->faker->paragraph,

        ];

        $this->post('/projects', $attributes)->assertRedirect('projects.index');

        $this->assertDatabaseHas("projects", $attributes);

        $this->get('/projects')->assertSee($attributes['title']);
    }

    public function test_a_user_can_view_their_project()
    {
        $this->be(User::factory()->create());
        $this->withoutExceptionHandling();
        $project = Project::factory()->create(['owner_id' => auth()->id()]);

        $this
            ->get('projects/'.$project->id)
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    public function test_an_authenticated_user_cannot_view_the_projects_of_others()
    {
        $this->be(User::factory()->create());
        $project = Project::factory()->create();

        $this->get($project->path())->assertStatus(403);
    }

    public function test_projects_require_a_title()
    {
        $this->actingAs(User::factory()->create());

        $attributes = Project::factory()->raw(['title' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    public function test_projects_require_a_description()
    {
        $this->actingAs(User::factory()->create());

        $attributes = Project::factory()->raw(['description' => '']);

        $this->post('/projects',$attributes)->assertSessionHasErrors('description');
    }

    public function test_it_belongs_to_an_owner()
    {
        $project = Project::factory()->create();

        $this->assertInstanceOf(User::class, $project->owner);

    }

}
