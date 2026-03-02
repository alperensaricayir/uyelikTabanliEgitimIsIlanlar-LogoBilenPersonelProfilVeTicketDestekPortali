<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ContentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CourseRequest;
use App\Models\ContentRevision;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Training::class);

        $query = Training::query()
            ->with('updatedBy')
            ->when($request->search, fn($q) => $q->where('title', 'like', '%' . $request->search . '%'))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->filled('premium'), fn($q) => $q->where('is_premium_only', (bool) $request->premium))
            ->orderBy($request->sort ?? 'created_at', $request->dir === 'asc' ? 'asc' : 'desc');

        $courses = $query->paginate(15)->withQueryString();

        return view('cms.courses.index', compact('courses'));
    }

    public function create()
    {
        $this->authorize('create', Training::class);
        return view('cms.courses.create');
    }

    public function store(CourseRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['updated_by'] = auth()->id();

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        if ($data['status'] === ContentStatus::Published->value && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $course = Training::create($data);

        if (!empty($data['description'])) {
            ContentRevision::record($course, 'description', $data['description'], auth()->id());
        }

        return redirect()->route('cms.courses.show', $course)
            ->with('success', 'Kurs oluşturuldu.');
    }

    public function show(Training $course, Request $request)
    {
        $this->authorize('update', $course);

        $tab = $request->tab ?? 'overview';
        $lessons = $course->lessons()->get();
        $revisions = $course->revisions()->with('user')->where('field', 'description')->take(5)->get();

        return view('cms.courses.show', compact('course', 'tab', 'lessons', 'revisions'));
    }

    public function edit(Training $course)
    {
        $this->authorize('update', $course);
        return view('cms.courses.edit', compact('course'));
    }

    public function update(CourseRequest $request, Training $course)
    {
        $this->authorize('update', $course);

        $data = $request->validated();
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['updated_by'] = auth()->id();

        // Thumbnail replace/remove
        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        } elseif ($request->boolean('remove_thumbnail')) {
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            $data['thumbnail'] = null;
        } else {
            unset($data['thumbnail']);
        }

        // Auto-set published_at
        if ($data['status'] === ContentStatus::Published->value && !$course->published_at) {
            $data['published_at'] = now();
        }

        // Record revision if description changed
        if (isset($data['description']) && $data['description'] !== $course->description) {
            ContentRevision::record($course, 'description', $course->description, auth()->id());
        }

        $course->update($data);

        return redirect()->route('cms.courses.show', $course)
            ->with('success', 'Kurs güncellendi.');
    }

    public function destroy(Training $course)
    {
        $this->authorize('delete', $course);
        $course->delete();

        return redirect()->route('cms.courses.index')
            ->with('success', 'Kurs silindi (geri yüklenebilir).');
    }

    public function trashed()
    {
        $this->authorize('restore', new Training());
        $courses = Training::onlyTrashed()->latest('deleted_at')->paginate(15);

        return view('cms.courses.trashed', compact('courses'));
    }

    public function restore(int $id)
    {
        $course = Training::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $course);
        $course->restore();

        return back()->with('success', 'Kurs geri yüklendi.');
    }

    public function bulk(Request $request)
    {
        $this->authorize('create', Training::class);

        $request->validate([
            'action' => ['required', 'in:publish,unpublish,delete'],
            'ids' => ['required', 'array'],
            'ids.*' => ['integer'],
        ]);

        $courses = Training::whereIn('id', $request->ids)->get();

        match ($request->action) {
            'publish' => $courses->each->update(['status' => ContentStatus::Published->value, 'published_at' => now()]),
            'unpublish' => $courses->each->update(['status' => ContentStatus::Draft->value]),
            'delete' => (function () use ($request) {
                    $this->authorize('delete', new Training());
                    Training::whereIn('id', $request->ids)->delete();
                })(),
        };

        return back()->with('success', 'Toplu işlem tamamlandı.');
    }
}
