@extends('layouts.app')

@section('title', 'Novo serviço | AgendeJá')

@section('content')
<div class="mb-4">
    <h3>Novo serviço</h3>
    <p class="text-muted">Preencha os dados para cadastrar um serviço.</p>
</div>

<div class="stat-card">
    <form action="{{ route('servicos.store') }}" method="POST">
        @csrf
        @include('servicos._form')
    </form>
</div>
@endsection
