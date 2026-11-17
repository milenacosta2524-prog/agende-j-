
@extends('layouts.app')

@section('title', 'Editar recebimento | AgendeJá')

@section('content')

<div class="topbar">
    <div>
        <h4 class="mb-1">Editar recebimento</h4>
        <small class="text-muted">Altere os dados do recebimento.</small>
    </div>

    <a href="{{ route('recebimentos.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>
        Voltar
    </a>
</div>

<div class="stat-card">

    <form action="{{ route('recebimentos.update', $recebimento) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>

            <input
                type="text"
                name="descricao"
                id="descricao"
                class="form-control @error('descricao') is-invalid @enderror"
                value="{{ old('descricao', $recebimento->descricao) }}"
                required
            >

            @error('descricao')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="valor" class="form-label">Valor recebido (R$)</label>

            <input
                type="number"
                name="valor"
                id="valor"
                class="form-control @error('valor') is-invalid @enderror"
                value="{{ old('valor', $recebimento->valor) }}"
                min="0.01"
                step="0.01"
                required
            >

            @error('valor')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="data_recebimento" class="form-label">
                Data do recebimento
            </label>

            <input
                type="date"
                name="data_recebimento"
                id="data_recebimento"
                class="form-control @error('data_recebimento') is-invalid @enderror"
                value="{{ old('data_recebimento', $recebimento->data_recebimento->format('Y-m-d')) }}"
                required
            >

            @error('data_recebimento')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle me-1"></i>
            Atualizar recebimento
        </button>

    </form>

</div>

@endsection
