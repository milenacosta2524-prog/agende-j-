@extends('layouts.app')

@section('title', 'Editar agendamento | AgendeJá')

@section('content')
<div class="mb-4">
    <h3>Editar agendamento</h3>
    <p class="text-muted">Atualize os dados do agendamento.</p>
</div>

<div class="stat-card">
    <form action="{{ route('agendamentos.update', $agendamento) }}" method="POST">
        @csrf
        @method('PUT')
        @include('agendamentos._form')
    </form>
</div>
@endsection
