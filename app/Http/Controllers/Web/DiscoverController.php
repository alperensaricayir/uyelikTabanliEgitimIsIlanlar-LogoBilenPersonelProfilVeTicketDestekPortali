<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DiscoverController extends Controller
{
    public function index()
    {
        /**
         * Discover skor formülü:
         * score = (hasFeaturedLinks ? 10 : 0) + (likes_count * 1) + (is_premium ? 5 : 0)
         *
         * SQLite ve MySQL için json_array_length / JSON_LENGTH ayrımı var.
         * MVP'de SQLite kullandığımızdan json_array_length kullanıyoruz.
         */
        $members = User::select('*')
            ->selectRaw("
                (CASE WHEN featured_links IS NOT NULL AND featured_links != '[]' AND featured_links != 'null' THEN 10 ELSE 0 END)
                + (likes_count * 1)
                + (CASE WHEN is_premium = 1 THEN 5 ELSE 0 END)
                AS discover_score
            ")
            ->where('role', 'member')
            ->orderByDesc('discover_score')
            ->paginate(15);

        return view('discover.index', compact('members'));
    }
}
