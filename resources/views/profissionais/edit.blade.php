@extends('layouts.app')

@section('title', 'Editar profissional | AgendeJá')

@section('content')
<div class="mb-4">
    <h3>Editar profissional</h3>
    <p class="text-muted">Atualize as informações do profissional.</p>
</div>

<div class="stat-card">
    <form action="{{ route('profissionais.update', $profissional) }}" method="POST">
        @csrf
        @method('PUT')
        @include('profissionais._form')
    </form>
</div>
@endsection
