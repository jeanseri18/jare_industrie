@extends('layouts.client')

@section('title', 'Mes Documents')

@section('content')
<x-page-header title="Mes documents" subtitle="Accédez et téléchargez vos documents officiels en toute sécurité" />
<x-alert />

@php $hasDocs = false; @endphp

<x-data-table>
    <x-slot:head>
        <tr>
            <th>Document</th>
            <th>Référence</th>
            <th>Projet</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
    </x-slot:head>
    @foreach($souscriptions as $s)
        @if($s->statut == 'SOLD')
            @php $hasDocs = true; @endphp
            <tr>
                <td><strong>Lettre définitive d'attribution</strong></td>
                <td>{{ $s->ref_souscription }}</td>
                <td>{{ $s->projet->nom ?? '—' }}</td>
                <td>{{ $s->updated_at->format('d/m/Y') }}</td>
                <td><a href="#" class="btn-secondary btn-sm"><i class="fas fa-download me-1"></i> PDF</a></td>
            </tr>
        @endif
        @if($s->attributionLot)
            @php $hasDocs = true; @endphp
            <tr>
                <td><strong>Attestation de réservation</strong></td>
                <td>{{ $s->ref_souscription }}</td>
                <td>{{ $s->projet->nom ?? '—' }}</td>
                <td>{{ $s->attributionLot->created_at->format('d/m/Y') }}</td>
                <td><a href="#" class="btn-secondary btn-sm"><i class="fas fa-download me-1"></i> PDF</a></td>
            </tr>
        @endif
    @endforeach
    @if(!$hasDocs)
        <tr>
            <td colspan="5">
                <x-empty-state title="Aucun document" message="Vos documents officiels seront disponibles dès validation de vos dossiers." />
            </td>
        </tr>
    @endif
</x-data-table>
@endsection
