
@extends('layouts.app')

@section('title', 'Relatório Financeiro | AgendeJá')

@section('content')

<div class="topbar">
    <div>
        <h4 class="mb-1">Relatório Financeiro</h4>
        <small class="text-muted">Acompanhe o desempenho financeiro do negócio.</small>
    </div>

    <a href="{{ route('recebimentos.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>
        Voltar ao financeiro
    </a>
</div>

{{-- Seleção de ano --}}
<div class="stat-card mb-4">

    <form action="{{ route('recebimentos.relatorio') }}" method="GET">
        <div class="row g-3 align-items-end">

            <div class="col-md-4">
                <label for="ano" class="form-label">Selecione o ano</label>

                <select name="ano" id="ano" class="form-select">
                    @for($i = now()->year; $i >= 2020; $i--)
                        <option value="{{ $i }}" {{ $ano == $i ? 'selected' : '' }}>
                            {{ $i }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search me-1"></i>
                    Consultar
                </button>
            </div>

        </div>
    </form>

</div>

{{-- Resumo anual --}}
<div class="row g-4 mb-4">

    <div class="col-md-6">
        <div class="stat-card">
            <i class="bi bi-cash-stack stat-icon"></i>
            <h6 class="text-muted mt-3">Total recebido em {{ $ano }}</h6>
            <h2 class="text-success">
                R$ {{ number_format($totalAno, 2, ',', '.') }}
            </h2>
        </div>
    </div>

    <div class="col-md-6">
        <div class="stat-card">
            <i class="bi bi-receipt stat-icon"></i>
            <h6 class="text-muted mt-3">Quantidade de recebimentos</h6>
            <h2>{{ $quantidade }}</h2>
        </div>
    </div>

</div>

{{-- Gráfico mensal --}}
<div class="stat-card mb-4">

    <h5 class="mb-1">Faturamento mensal</h5>

    <p class="text-muted mb-4">
        Comparativo dos recebimentos ao longo de {{ $ano }}.
    </p>

    <canvas id="graficoFinanceiro" height="110"></canvas>

</div>

{{-- Exportação --}}
<div class="stat-card">

    <h5 class="mb-2">Exportar relatório</h5>

    <p class="text-muted">
        Baixe os recebimentos do ano selecionado em um arquivo compatível com Excel.
    </p>

    <a
        href="{{ route('recebimentos.exportar', [
            'data_inicio' => $ano . '-01-01',
            'data_fim' => $ano . '-12-31'
        ]) }}"
        class="btn btn-success"
    >
        <i class="bi bi-file-earmark-excel me-1"></i>
        Exportar para Excel
    </a>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const elemento = document.getElementById('graficoFinanceiro');

    if (!elemento) {
        return;
    }

    new Chart(elemento, {
        type: 'bar',

        data: {
            labels: @json($nomesMeses),

            datasets: [{
                label: 'Recebimentos (R$)',
                data: @json($dadosMensais),
                backgroundColor: '#dc2626',
                borderRadius: 6
            }]
        },

        options: {
            responsive: true,

            plugins: {
                legend: {
                    display: false
                }
            },

            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

});
</script>
@endpush
