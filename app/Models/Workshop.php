<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Workshop extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'date_time',
        'duration_minutes',
        'capacity',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'date_time' => 'datetime',
            'duration_minutes' => 'integer',
            'capacity' => 'integer',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function confirmedRegistrations(): HasMany
    {
        return $this->registrations()->where('status', 'confirmed');
    }

    public function waitingRegistrations(): HasMany
    {
        return $this->registrations()->where('status', 'waiting')->orderBy('position');
    }

    public function confirmedCount(): int
    {
        return $this->confirmedRegistrations()->count();
    }

    public function isFull(): bool
    {
        return $this->confirmedCount() >= $this->capacity;
    }

    public function endTime(): Carbon
    {
        return $this->date_time->copy()->addMinutes($this->duration_minutes);
    }

    public function promoteFromWaitingList(): void
    {
        $next = $this->waitingRegistrations()->first();
        if ($next) {
            $next->update(['status' => 'confirmed', 'position' => null]);
            $this->reorderWaitingList();
        }
    }

    public function reorderWaitingList(): void
    {
        $waiting = $this->waitingRegistrations()->get();
        foreach ($waiting as $i => $reg) {
            $reg->update(['position' => $i + 1]);
        }
    }
}
