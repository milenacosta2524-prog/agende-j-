@extends('layouts.app')

@section('title', 'Novo profissional | AgendeJá')

@section('content')
<div class="mb-4">
    <h3>Novo profissional</h3>
    <p class="text-muted">Preencha os dados para cadastrar um profissional.</p>
</div>

<div class="stat-card">
    <form action="{{ route('profissionais.store') }}" method="POST">
        @csrf
        @include('profissionais._form')
    </form>
</div>
@endsection
