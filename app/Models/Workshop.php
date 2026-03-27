<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

/**
 * Rappresenta un workshop formativo all'interno dell'academy.
 *
 * Ogni workshop ha una data, una durata e una capienza massima.
 * Una volta raggiunta la capienza, le nuove iscrizioni finiscono
 * in waiting list (coda FIFO). Quando qualcuno cancella la propria
 * iscrizione confermata, il primo in coda viene promosso automaticamente.
 */
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

    // ──────────────────────────────────────────────
    //  Relazioni
    // ──────────────────────────────────────────────

    /** L'admin che ha creato il workshop. */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** Tutte le iscrizioni (confermate + in attesa). */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    /** Solo le iscrizioni confermate (posti occupati). */
    public function confirmedRegistrations(): HasMany
    {
        return $this->registrations()->where('status', 'confirmed');
    }

    /** Iscrizioni in attesa, ordinate per posizione in coda (FIFO). */
    public function waitingRegistrations(): HasMany
    {
        return $this->registrations()->where('status', 'waiting')->orderBy('position');
    }

    // ──────────────────────────────────────────────
    //  Helper di stato
    // ──────────────────────────────────────────────

    public function confirmedCount(): int
    {
        return $this->confirmedRegistrations()->count();
    }

    public function isFull(): bool
    {
        return $this->confirmedCount() >= $this->capacity;
    }

    /** Calcola l'orario di fine sommando la durata alla data di inizio. */
    public function endTime(): Carbon
    {
        return $this->date_time->copy()->addMinutes($this->duration_minutes);
    }

    // ──────────────────────────────────────────────
    //  Logica waiting list
    // ──────────────────────────────────────────────

    /**
     * Promuove il primo utente in coda da "waiting" a "confirmed".
     *
     * Viene chiamato ogni volta che un partecipante confermato cancella
     * la propria iscrizione, liberando così un posto. Dopo la promozione
     * riordiniamo le posizioni rimanenti per mantenerle sequenziali.
     */
    public function promoteFromWaitingList(): void
    {
        $next = $this->waitingRegistrations()->first();

        if ($next) {
            $next->update(['status' => 'confirmed', 'position' => null]);
            $this->reorderWaitingList();
        }
    }

    /**
     * Ricalcola le posizioni nella waiting list (1, 2, 3...).
     *
     * Necessario dopo ogni promozione o cancellazione per evitare
     * "buchi" nella numerazione (es. 1, 3, 5 → 1, 2, 3).
     */
    public function reorderWaitingList(): void
    {
        $waiting = $this->waitingRegistrations()->get();

        foreach ($waiting as $i => $reg) {
            $reg->update(['position' => $i + 1]);
        }
    }
}
