<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Training;
use Illuminate\Http\Request;

class TrainingEnrollmentController extends Controller
{
    public function store(Request $request, Training $training)
    {
        $user = auth()->user();

        if ($training->hasUserEnrolled($user)) {
            return back()->with('info', 'Bu eğitime zaten kayıtlısınız.');
        }

        if ($training->is_premium_only && !$user->isPremium()) {
            return redirect()->route('premium.services')->with('error', 'Bu eğitime kayıt olmak için Premium üyeliğe ihtiyacınız var.');
        }

        $training->enrollments()->create([
            'user_id' => $user->id,
        ]);

        return back()->with('success', 'Eğitime başarıyla kayıt oldunuz!');
    }
}
