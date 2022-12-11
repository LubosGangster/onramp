<?php

namespace Tests\Feature;

use App\Models\Resource;
use App\Models\Term;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResourceAccessTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function users_with_user_role_cannot_update_resources()
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->assertFalse($user->can('update', new Resource));
    }

    /** @test */
    function users_with_editor_role_can_update_resources()
    {
        $user = User::factory()->create(['role' => 'editor']);

        $this->assertTrue($user->can('update', new Resource));
    }

    /** @test */
    function users_with_admin_role_can_update_resources()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->assertTrue($user->can('update', new Resource));
    }
    /** @test */
    function resource_belongs_to_many_terms()
    {
        $resource = Resource::factory()->create();
        $term = Term::factory()->create();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $resource->terms);
    }
}
