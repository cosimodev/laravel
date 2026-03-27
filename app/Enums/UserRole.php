<?php

namespace App\Enums;

/**
 * Definisce i ruoli disponibili nell'applicazione.
 *
 * Per ora gestiamo solo due ruoli: l'admin che crea e gestisce i workshop,
 * e l'employee che può iscriversi. In futuro si potrebbe estendere
 * (es. "trainer", "hr_manager") senza toccare la logica esistente,
 * dato che usiamo un backed enum con valori stringa.
 */
enum UserRole: string
{
    case Admin = 'admin';
    case Employee = 'employee';
}
