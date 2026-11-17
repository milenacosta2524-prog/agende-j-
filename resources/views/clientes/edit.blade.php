@extends('layouts.app')

@section('title', 'Editar cliente | AgendeJá')

@section('content')

<div class="mb-4">
    <h3>Editar cliente</h3>
    <p class="text-muted">Atualize as informações do cliente.</p>
</div>

<div class="stat-card">
    <form action="{{ route('clientes.update', $cliente) }}" method="POST">
        @csrf
        @method('PUT')

        @include('clientes._form')
    </form>
</div>

@endsection