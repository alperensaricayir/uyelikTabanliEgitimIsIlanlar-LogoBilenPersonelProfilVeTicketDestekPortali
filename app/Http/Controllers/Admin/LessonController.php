<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ContentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LessonRequest;
use App\Models\ContentRevision;
use App\Models\Lesson;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LessonController extends Controller
{
    public function index(Training $course)
    {
        $this->authorize('viewAny', Lesson::class);
        $lessons = $course->lessons()->withTrashed(false)->get();

        return view('cms.lessons.index', compact('course', 'lessons'));
    }

    public function create(Training $course)
    {
        $this->authorize('create', Lesson::class);
        return view('cms.lessons.create', compact('course'));
    }

    public function store(LessonRequest $request, Training $course)
    {
        $data = $request->validated();
        $data['training_id'] = $course->id;
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['updated_by'] = auth()->id();
        $data['sort_order'] = $data['sort_order'] ?? $course->lessons()->max('sort_order') + 1;

        if ($data['status'] === ContentStatus::Published->value && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $lesson = Lesson::create($data);

        if (!empty($data['content'])) {
            ContentRevision::record($lesson, 'content', $data['content'], auth()->id());
        }

        return redirect()->route('cms.courses.lessons.index', $course)
            ->with('success', 'Ders oluşturuldu.');
    }

    public function edit(Training $course, Lesson $lesson)
    {
        $this->authorize('update', $lesson);
        $revisions = $lesson->revisions()->where('field', 'content')->take(5)->get();

        return view('cms.lessons.edit', compact('course', 'lesson', 'revisions'));
    }

    public function update(LessonRequest $request, Training $course, Lesson $lesson)
    {
        $this->authorize('update', $lesson);

        $data = $request->validated();
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['updated_by'] = auth()->id();

        if ($data['status'] === ContentStatus::Published->value && !$lesson->published_at) {
            $data['published_at'] = now();
        }

        // Record revision if content changed
        if (isset($data['content']) && $data['content'] !== $lesson->content) {
            ContentRevision::record($lesson, 'content', $lesson->content ?? '', auth()->id());
        }

        $lesson->update($data);

        return redirect()->route('cms.courses.lessons.index', $course)
            ->with('success', 'Ders güncellendi.');
    }

    public function destroy(Training $course, Lesson $lesson)
    {
        $this->authorize('delete', $lesson);
        $lesson->delete();

        return back()->with('success', 'Ders silindi (geri yüklenebilir).');
    }

    public function reorder(Request $request, Training $course)
    {
        $this->authorize('update', new Lesson(['training_id' => $course->id]));

        $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer'],
        ]);

        foreach ($request->order as $index => $lessonId) {
            Lesson::where('id', $lessonId)
                ->where('training_id', $course->id)
                ->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    public function trashed(Training $course)
    {
        $this->authorize('restore', Lesson::class);
        $lessons = Lesson::onlyTrashed()
            ->where('training_id', $course->id)
            ->latest('deleted_at')
            ->paginate(15);

        return view('cms.lessons.trashed', compact('course', 'lessons'));
    }

    public function restore(Training $course, int $id)
    {
        $lesson = Lesson::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $lesson);
        $lesson->restore();

        return back()->with('success', 'Ders geri yüklendi.');
    }
}
