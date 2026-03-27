<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validazione per la creazione e modifica di un workshop.
 *
 * Usato sia dallo store che dall'update — le regole sono le stesse
 * perché in entrambi i casi vogliamo tutti i campi obbligatori.
 * La data deve essere futura per evitare workshop nel passato.
 */
class WorkshopRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // L'autorizzazione è gestita dal middleware role:admin
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'date_time' => ['required', 'date', 'after:now'],
            'duration_minutes' => ['required', 'integer', 'min:15', 'max:480'],
            'capacity' => ['required', 'integer', 'min:1'],
        ];
    }
}
