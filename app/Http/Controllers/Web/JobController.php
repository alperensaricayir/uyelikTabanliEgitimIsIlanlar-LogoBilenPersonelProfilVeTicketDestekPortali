<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\JobAlert;
use App\Models\JobPosting;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $jobs = JobPosting::active()
            ->latest()
            ->paginate(12);

        $alert = auth()->check()
            ? auth()->user()->jobAlerts()->where('is_active', true)->first()
            : null;

        return view('jobs.index', compact('jobs', 'alert'));
    }

    public function show(JobPosting $job)
    {
        return view('jobs.show', compact('job'));
    }

    /** Uyarı (alert) oluştur ya da güncelle. */
    public function storeAlert(Request $request)
    {
        // Clean empty keywords before validation
        $keywords = array_values(array_filter($request->keywords ?? [], fn($v) => filled($v)));
        $request->merge(['keywords' => $keywords]);

        $request->validate([
            'keywords' => 'required|array|min:1',
            'keywords.*' => 'string|max:50',
        ]);

        JobAlert::updateOrCreate(
            ['user_id' => auth()->id()],
            ['keywords' => $request->keywords, 'is_active' => true]
        );

        return back()->with('success', 'İş uyarısı kaydedildi!');
    }
}
