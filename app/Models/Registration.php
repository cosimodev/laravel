<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Iscrizione di un utente a un workshop.
 *
 * Ogni registrazione ha uno stato: "confirmed" (posto assegnato)
 * oppure "waiting" (in lista d'attesa). La posizione in coda è
 * valorizzata solo per lo stato waiting e segue ordine FIFO.
 *
 * Il vincolo UNIQUE(user_id, workshop_id) in DB impedisce doppie
 * iscrizioni allo stesso workshop — lo controlliamo anche lato
 * applicativo nel controller per dare un messaggio d'errore chiaro.
 */
class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'workshop_id',
        'status',
        'position',
    ];

    protected function casts(): array
    {
        return [
            'position' => 'integer',
        ];
    }

    /** L'utente che si è iscritto. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Il workshop a cui è iscritto. */
    public function workshop(): BelongsTo
    {
        return $this->belongsTo(Workshop::class);
    }
}
