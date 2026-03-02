<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LessonController extends Controller
{
    /**
     * Display the specified lesson.
     */
    public function show(Training $training, Lesson $lesson): View|\Illuminate\Http\RedirectResponse
    {
        // 1. Ensure the lesson belongs to the training
        if ($lesson->training_id !== $training->id) {
            abort(404);
        }

        // 2. Access Control
        $hasAccess = false;

        // Preview lessons are available to everyone
        if ($lesson->is_preview) {
            $hasAccess = true;
        } else {
            // Non-preview lessons require auth & enrollment
            if (auth()->check()) {
                if ($training->hasUserEnrolled(auth()->user())) {
                    $hasAccess = true;
                }
            }
        }

        if (!$hasAccess) {
            return redirect()->route('trainings.show', $training)
                ->with('error', 'Bu dersi izleyebilmek için eğitime kayıt olmanız gerekmektedir.');
        }

        // 3. Load other available lessons for the sidebar navigation
        $otherLessons = $training->lessons()
            ->visible()
            ->orderBy('sort_order')
            ->get();

        return view('trainings.lessons.show', [
            'training' => $training,
            'lesson' => $lesson,
            'otherLessons' => $otherLessons,
            'isEnrolled' => auth()->check() ? $training->hasUserEnrolled(auth()->user()) : false,
        ]);
    }
}
