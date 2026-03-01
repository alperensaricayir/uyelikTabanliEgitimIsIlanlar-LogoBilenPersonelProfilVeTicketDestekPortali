<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Like;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class LikeController extends Controller
{
    /**
     * Polimorfik beğeni oluşturma.
     * Kural: Bir kullanıcı 24 saat içinde en fazla 20 beğeni atabilir.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'likeable_type' => 'required|string|in:App\\Models\\Training,App\\Models\\JobPosting,App\\Models\\User',
            'likeable_id' => 'required|integer',
        ]);

        $user = $request->user();
        $key = 'likes:' . $user->id;

        // 24 saatlik pencerede 20 beğeni sınırı
        if (RateLimiter::tooManyAttempts($key, $maxAttempts = 20)) {
            $seconds = RateLimiter::availableIn($key);

            return response()->json([
                'message' => 'Günlük beğeni limitiniz doldu.',
                'retry_after_seconds' => $seconds,
            ], 429);
        }

        // SQL unique kısıtı zaten tekrar beğeniyi engelliyor.
        // Uygulama seviyesinde de kontrol edelim:
        $already = Like::where('user_id', $user->id)
            ->where('likeable_type', $request->likeable_type)
            ->where('likeable_id', $request->likeable_id)
            ->exists();

        if ($already) {
            return response()->json(['message' => 'Bu içeriği zaten beğendiniz.'], 409);
        }

        Like::create([
            'user_id' => $user->id,
            'likeable_type' => $request->likeable_type,
            'likeable_id' => $request->likeable_id,
        ]);

        // Limit sayacını güncelle (TTL: 24 saat = 86400 saniye)
        RateLimiter::hit($key, 86400);

        // likes_count önbelleklemesi (denormalized counter)
        if ($request->likeable_type === 'App\\Models\\User') {
            \App\Models\User::where('id', $request->likeable_id)->increment('likes_count');
        }

        return response()->json(['message' => 'Beğeni eklendi.'], 201);
    }

    /** Beğeniyi geri al. */
    public function destroy(Request $request): JsonResponse
    {
        $request->validate([
            'likeable_type' => 'required|string',
            'likeable_id' => 'required|integer',
        ]);

        $user = $request->user();

        $like = Like::where('user_id', $user->id)
            ->where('likeable_type', $request->likeable_type)
            ->where('likeable_id', $request->likeable_id)
            ->first();

        if (!$like) {
            return response()->json(['message' => 'Beğeni bulunamadı.'], 404);
        }

        $like->delete();

        if ($request->likeable_type === 'App\\Models\\User') {
            \App\Models\User::where('id', $request->likeable_id)->decrement('likes_count');
        }

        return response()->json(['message' => 'Beğeni kaldırıldı.'], 200);
    }
}
