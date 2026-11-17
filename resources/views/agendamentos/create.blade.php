@extends('layouts.app')

@section('title', 'Novo agendamento | AgendeJá')

@section('content')
<div class="mb-4">
    <h3>Novo agendamento</h3>
    <p class="text-muted">Cadastre um novo atendimento para o cliente.</p>
</div>

<div class="stat-card">
    <form action="{{ route('agendamentos.store') }}" method="POST">
        @csrf
        @include('agendamentos._form')
    </form>
</div>
@endsection
