@extends('layouts.app')

@section('title', 'Novo cliente | AgendeJá')

@section('content')

<div class="mb-4">
    <h3>Novo cliente</h3>
    <p class="text-muted">Preencha os dados para cadastrar um cliente.</p>
</div>

<div class="stat-card">
    <form action="{{ route('clientes.store') }}" method="POST">
        @csrf

        @include('clientes._form')
    </form>
</div>

@endsection