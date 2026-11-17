
@extends('layouts.app')

@section('title', 'Novo recebimento | AgendeJá')

@section('content')

<div class="topbar">
    <div>
        <h4 class="mb-1">Novo recebimento</h4>
        <small class="text-muted">Registre um valor que entrou no caixa.</small>
    </div>

    <a href="{{ route('recebimentos.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>
        Voltar
    </a>
</div>

<div class="stat-card">
    <form action="{{ route('recebimentos.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <input
                type="text"
                name="descricao"
                id="descricao"
                class="form-control @error('descricao') is-invalid @enderror"
                value="{{ old('descricao') }}"
                placeholder="Ex.: Corte de cabelo, manicure, venda de produto"
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
                value="{{ old('valor') }}"
                placeholder="0,00"
                min="0.01"
                step="0.01"
                required
            >
            @error('valor')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="data_recebimento" class="form-label">Data do recebimento</label>
            <input
                type="date"
                name="data_recebimento"
                id="data_recebimento"
                class="form-control @error('data_recebimento') is-invalid @enderror"
                value="{{ old('data_recebimento', now()->format('Y-m-d')) }}"
                required
            >
            @error('data_recebimento')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle me-1"></i>
            Salvar recebimento
        </button>
    </form>
</div>

@endsection
