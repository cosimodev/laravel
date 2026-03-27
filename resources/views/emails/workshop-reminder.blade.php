<x-mail::message>
# Promemoria Workshop

Il workshop **{{ $workshop->title }}** si terrà domani!

**Data:** {{ $workshop->date_time->format('d/m/Y H:i') }}
**Durata:** {{ $workshop->duration_minutes }} minuti

{{ $workshop->description }}

Grazie,<br>
{{ config('app.name') }}
</x-mail::message>
