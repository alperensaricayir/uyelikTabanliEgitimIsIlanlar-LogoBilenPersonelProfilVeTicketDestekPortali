<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ContentRevision extends Model
{
    use HasFactory;

    protected $fillable = [
        'revisionable_type',
        'revisionable_id',
        'field',
        'value',
        'user_id',
    ];

    public function revisionable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Record a new revision for the given model + field.
     * Keeps only the last $maxRevisions entries.
     */
    public static function record(
        Model $model,
        string $field,
        string $value,
        ?int $userId = null,
        int $maxRevisions = 5
    ): void {
        static::create([
            'revisionable_type' => get_class($model),
            'revisionable_id' => $model->getKey(),
            'field' => $field,
            'value' => $value,
            'user_id' => $userId,
        ]);

        // Prune to keep only the most recent $maxRevisions
        $ids = static::where('revisionable_type', get_class($model))
            ->where('revisionable_id', $model->getKey())
            ->where('field', $field)
            ->orderByDesc('created_at')
            ->pluck('id')
            ->skip($maxRevisions);

        if ($ids->isNotEmpty()) {
            static::whereIn('id', $ids)->delete();
        }
    }
}
