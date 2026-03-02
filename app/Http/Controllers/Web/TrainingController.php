<?php

namespace App\Http\Controllers\Web;

use App\Enums\ContentStatus;
use App\Http\Controllers\Controller;
use App\Models\Training;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    /** Eğitim listesi – yalnızca yayımlananları göster. */
    public function index()
    {
        $trainings = Training::published()
            ->orderByDesc('published_at')
            ->paginate(12);

        return view('trainings.index', compact('trainings'));
    }

    /** Eğitim detayı – gated alanlar policy ile kontrol edilir. */
    public function show(Training $training)
    {
        // 404 if not published (unless admin/editor)
        if (!$training->isPublished() && !(auth()->check() && auth()->user()->isAdminOrEditor())) {
            abort(404);
        }

        // Gated content viewable if they are enrolled, or if they are admin/editor.
        $canViewGated = false;
        if (auth()->check()) {
            $user = auth()->user();
            $canViewGated = $user->isAdminOrEditor() || $training->hasUserEnrolled($user);
        }

        // Show published lessons + preview lessons visible without enrollment
        $lessons = $training->lessons()
            ->where(function ($q) {
                $q->where('status', ContentStatus::Published)
                    ->orWhere('is_preview', true);
            })
            ->get();

        return view('trainings.show', compact('training', 'canViewGated', 'lessons'));
    }
}
