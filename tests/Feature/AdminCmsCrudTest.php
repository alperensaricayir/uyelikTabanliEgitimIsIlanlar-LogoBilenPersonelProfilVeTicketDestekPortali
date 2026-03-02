<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Training;
use App\Models\Lesson;
use App\Enums\ContentStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCmsCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // AdminUserSeeder creates an admin with this email, but we'll manually instance one.
        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);
    }

    public function test_admin_can_view_courses_index()
    {
        $response = $this->actingAs($this->admin)->get(route('cms.courses.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_create_a_course()
    {
        $payload = [
            'title' => 'Feature Test Course',
            'slug' => 'feature-test-course',
            'description' => 'A test description',
            'is_premium_only' => false,
            'status' => 'draft',
        ];

        $response = $this->actingAs($this->admin)->post(route('cms.courses.store'), $payload);

        $course = Training::where('slug', 'feature-test-course')->first();

        $response->assertRedirect(route('cms.courses.show', $course));
        $this->assertDatabaseHas('trainings', ['title' => 'Feature Test Course']);
    }

    public function test_admin_can_update_a_course()
    {
        $course = Training::create([
            'title' => 'Old Title',
            'slug' => 'old-title',
            'description' => 'A simulated old description',
            'status' => 'draft',
            'is_premium_only' => false,
            'updated_by' => $this->admin->id,
        ]);

        $payload = [
            'title' => 'Updated Title',
            'slug' => 'updated-title',
            'description' => 'Updated description content',
            'status' => 'published',
            'is_premium_only' => true,
        ];

        $response = $this->actingAs($this->admin)->put(route('cms.courses.update', $course), $payload);

        $response->assertRedirect(route('cms.courses.show', $course));
        $this->assertDatabaseHas('trainings', ['id' => $course->id, 'title' => 'Updated Title', 'is_premium_only' => true]);
    }

    public function test_admin_can_delete_and_restore_a_course()
    {
        $course = Training::create([
            'title' => 'Delete Title',
            'slug' => 'delete-title',
            'description' => 'A simulated short desc',
            'status' => 'draft',
            'is_premium_only' => false,
            'updated_by' => $this->admin->id,
        ]);

        // Soft Delete
        $response = $this->actingAs($this->admin)->delete(route('cms.courses.destroy', $course));
        $response->assertRedirect(route('cms.courses.index'));
        $this->assertSoftDeleted('trainings', ['id' => $course->id]);

        // Restore
        $response = $this->actingAs($this->admin)->post(route('cms.courses.restore', $course->id));
        $response->assertRedirect();
        $this->assertNotSoftDeleted('trainings', ['id' => $course->id]);
    }

    public function test_admin_can_perform_bulk_actions_on_courses()
    {
        $course1 = Training::create([
            'title' => 'Bulk 1',
            'slug' => 'bulk-1',
            'description' => 'Bulk demo 1',
            'status' => 'draft',
            'is_premium_only' => false,
            'updated_by' => $this->admin->id,
        ]);

        $course2 = Training::create([
            'title' => 'Bulk 2',
            'slug' => 'bulk-2',
            'description' => 'Bulk demo 2',
            'status' => 'draft',
            'is_premium_only' => false,
            'updated_by' => $this->admin->id,
        ]);

        $payload = [
            'action' => 'publish',
            'ids' => [$course1->id, $course2->id]
        ];

        $response = $this->actingAs($this->admin)->post(route('cms.courses.bulk'), $payload);
        $response->assertRedirect();

        $this->assertDatabaseHas('trainings', ['id' => $course1->id, 'status' => 'published']);
        $this->assertDatabaseHas('trainings', ['id' => $course2->id, 'status' => 'published']);
    }

    // --- LESSON CRUD TESTS ---

    public function test_admin_can_view_lessons_index()
    {
        $course = Training::create([
            'title' => 'Lesson Test Course',
            'slug' => 'lesson-test-course',
            'description' => 'Desc',
            'status' => 'draft',
            'is_premium_only' => false,
            'updated_by' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->get(route('cms.courses.lessons.index', $course));
        $response->assertStatus(200);
    }

    public function test_admin_can_create_a_lesson()
    {
        $course = Training::create([
            'title' => 'Course for Lesson Create',
            'slug' => 'course-4-lesson-create',
            'description' => 'Desc',
            'status' => 'draft',
            'is_premium_only' => false,
            'updated_by' => $this->admin->id,
        ]);

        $payload = [
            'title' => 'Feature Test Lesson',
            'slug' => 'feature-test-lesson',
            'content' => 'A test description for lesson',
            'video_url' => 'https://youtube.com',
            'is_preview' => false,
            'status' => 'published',
            'sort_order' => 1,
        ];

        $response = $this->actingAs($this->admin)->post(route('cms.courses.lessons.store', $course), $payload);

        $response->assertRedirect(route('cms.courses.lessons.index', $course));
        $this->assertDatabaseHas('lessons', ['title' => 'Feature Test Lesson', 'training_id' => $course->id]);
    }

    public function test_admin_can_update_a_lesson()
    {
        $course = Training::create([
            'title' => 'Course for Lesson Update',
            'slug' => 'course-4-lesson-update',
            'description' => 'Desc',
            'status' => 'draft',
            'is_premium_only' => false,
            'updated_by' => $this->admin->id,
        ]);

        $lesson = Lesson::create([
            'training_id' => $course->id,
            'title' => 'Old Lesson Title',
            'slug' => 'old-lesson-title',
            'content' => 'Old content',
            'status' => 'draft',
            'is_preview' => false,
            'updated_by' => $this->admin->id,
        ]);

        $payload = [
            'title' => 'Updated Lesson Title',
            'slug' => 'updated-lesson-title',
            'content' => 'Updated content',
            'status' => 'published',
            'video_url' => 'https://vimeo.com',
            'is_preview' => true,
        ];

        $this->withoutExceptionHandling();
        $response = $this->actingAs($this->admin)->put(route('cms.lessons.update', $lesson), $payload);

        $response->assertRedirect(route('cms.courses.lessons.index', $course));
        $this->assertDatabaseHas('lessons', ['id' => $lesson->id, 'title' => 'Updated Lesson Title', 'is_preview' => true]);
    }

    public function test_admin_can_delete_a_lesson()
    {
        $course = Training::create([
            'title' => 'Course for Lesson Delete',
            'slug' => 'course-4-lesson-delete',
            'description' => 'Desc',
            'status' => 'draft',
            'is_premium_only' => false,
            'updated_by' => $this->admin->id,
        ]);

        $lesson = Lesson::create([
            'training_id' => $course->id,
            'title' => 'Lesson to delete',
            'slug' => 'lesson-to-delete',
            'content' => 'content',
            'status' => 'draft',
            'is_preview' => false,
            'updated_by' => $this->admin->id,
        ]);

        // Soft Delete
        $response = $this->actingAs($this->admin)->delete(route('cms.lessons.destroy', $lesson));
        $response->assertRedirect();
        $this->assertSoftDeleted('lessons', ['id' => $lesson->id]);
    }
}
