<?php

namespace Tests\Feature;

use App\Models\Completion;
use App\Models\Module;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SkillTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $carbon;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->module = Module::factory()->create();
        $this->skill = Skill::factory()->create(['module_id' => $this->module->id]);
        $this->completion = Completion::create([
            'completable_type' => $this->skill->getMorphClass(),
            'completable_id' => $this->skill->id,
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function users_completed_skills()
    {
        $user = User::factory()->create();
        $skillA = Skill::factory()->create();
        $skillB = Skill::factory()->create();
        $user->complete($skillA);
        $user->complete($skillB);
        //+1 cause setup
        $this->assertEquals(3, Completion::count());
    }

    /** @test */
    public function a_skill_belongs_to_a_module() {
        $module = Module::factory()->create();
        $skill = Skill::factory()->create(['module_id' => $module->id]);

        $this->assertInstanceOf(Module::class, $skill->module);
    }

    /** @test  */
    public function skill_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('skills', [
                'id','name', 'is_bonus', 'module_id', 'created_at', 'updated_at'
            ]), 1);
    }

    /** @test  */
    public function a_skill_morphs_many_completions()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->skill->completions);
    }
}
