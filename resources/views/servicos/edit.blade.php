@extends('layouts.app')

@section('title', 'Editar serviço | AgendeJá')

@section('content')
<div class="mb-4">
    <h3>Editar serviço</h3>
    <p class="text-muted">Atualize as informações do serviço.</p>
</div>

<div class="stat-card">
    <form action="{{ route('servicos.update', $servico) }}" method="POST">
        @csrf
        @method('PUT')
        @include('servicos._form')
    </form>
</div>
@endsection
