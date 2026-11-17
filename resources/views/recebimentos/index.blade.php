
@extends('layouts.app')

@section('title', 'Financeiro | AgendeJá')

@section('content')

<div class="topbar">
    <div>
        <h4 class="mb-1">Financeiro</h4>
        <small class="text-muted">Controle os valores recebidos.</small>
    </div>

    <div class="d-flex gap-2 flex-wrap">

        <a href="{{ route('recebimentos.relatorio') }}"
           class="btn btn-outline-danger">
            <i class="bi bi-bar-chart-line me-1"></i>
            Relatório financeiro
        </a>

        <a href="{{ route('recebimentos.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>
            Novo recebimento
        </a>

    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Cartões financeiros --}}
<div class="row g-4 mb-4">

    <div class="col-md-6">
        <div class="stat-card">
            <i class="bi bi-cash-stack stat-icon"></i>
            <h6 class="text-muted mt-3">Recebido hoje</h6>
            <h2>R$ {{ number_format($totalHoje, 2, ',', '.') }}</h2>
        </div>
    </div>

    <div class="col-md-6">
        <div class="stat-card">
            <i class="bi bi-graph-up-arrow stat-icon"></i>
            <h6 class="text-muted mt-3">Recebido neste mês</h6>
            <h2>R$ {{ number_format($totalMes, 2, ',', '.') }}</h2>
        </div>
    </div>

</div>

{{-- Filtros por período --}}
<div class="stat-card mb-4">

    <h5 class="mb-3">Filtrar recebimentos</h5>

    <form action="{{ route('recebimentos.index') }}" method="GET">

        <div class="row g-3 align-items-end">

            <div class="col-md-4">
                <label for="data_inicio" class="form-label">
                    Data inicial
                </label>

                <input
                    type="date"
                    name="data_inicio"
                    id="data_inicio"
                    class="form-control"
                    value="{{ $dataInicio ?? '' }}"
                >
            </div>

            <div class="col-md-4">
                <label for="data_fim" class="form-label">
                    Data final
                </label>

                <input
                    type="date"
                    name="data_fim"
                    id="data_fim"
                    class="form-control"
                    value="{{ $dataFim ?? '' }}"
                >
            </div>

            <div class="col-md-4 d-flex gap-2">

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search me-1"></i>
                    Filtrar
                </button>

                <a href="{{ route('recebimentos.index') }}"
                   class="btn btn-outline-secondary">
                    Limpar
                </a>

            </div>

        </div>

    </form>

    <div class="mt-3">
        <span class="text-muted">Total do período filtrado:</span>

        <h4 class="text-success mb-0">
            R$ {{ number_format($totalFiltrado ?? 0, 2, ',', '.') }}
        </h4>
    </div>

</div>

{{-- Histórico de recebimentos --}}
<div class="stat-card">

    <div class="mb-3">
        <h5 class="mb-1">Histórico de recebimentos</h5>

        <small class="text-muted">
            Todos os valores registrados no sistema.
        </small>
    </div>

    <div class="table-responsive">

        <table class="table table-hover align-middle">

            <thead>
                <tr>
                    <th>Data</th>
                    <th>Descrição</th>
                    <th>Valor recebido</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>

            <tbody>

                @forelse($recebimentos as $recebimento)

                    <tr>
                        <td>
                            {{ $recebimento->data_recebimento->format('d/m/Y') }}
                        </td>

                        <td>{{ $recebimento->descricao }}</td>

                        <td class="fw-semibold text-success">
                            R$ {{ number_format($recebimento->valor, 2, ',', '.') }}
                        </td>

                        <td class="text-end">

                            <div class="d-flex justify-content-end gap-2">

                                <a
                                    href="{{ route('recebimentos.edit', $recebimento) }}"
                                    class="btn btn-sm btn-outline-primary"
                                    title="Editar recebimento"
                                >
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form
                                    action="{{ route('recebimentos.destroy', $recebimento) }}"
                                    method="POST"
                                    onsubmit="return confirm('Deseja realmente excluir este recebimento?')"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-outline-danger"
                                        title="Excluir recebimento"
                                    >
                                        <i class="bi bi-trash"></i>
                                    </button>

                                </form>

                            </div>

                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            Nenhum recebimento encontrado para este período.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
