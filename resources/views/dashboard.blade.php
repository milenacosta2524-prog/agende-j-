
@extends('layouts.app')

@section('title', 'Dashboard | AgendeJá')

@section('content')

<div class="topbar">
    <div>
        <h4 class="mb-1">Painel administrativo</h4>
        <small class="text-muted">Bem-vindo ao AgendeJá!</small>
    </div>

    <div>
        <i class="bi bi-person-circle fs-3 text-primary"></i>
    </div>
</div>

<h4 class="mb-4">Visão geral</h4>

<div class="row g-4">

    <div class="col-md-6 col-xl-3">
        <a href="{{ route('clientes.index') }}" class="text-decoration-none text-dark">
            <div class="stat-card">
                <i class="bi bi-people stat-icon"></i>
                <h6 class="text-muted mt-3">Clientes</h6>
                <h2>{{ $totalClientes }}</h2>
                <small class="text-danger">Acessar clientes <i class="bi bi-arrow-right"></i></small>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-xl-3">
        <a href="{{ route('profissionais.index') }}" class="text-decoration-none text-dark">
            <div class="stat-card">
                <i class="bi bi-person-badge stat-icon"></i>
                <h6 class="text-muted mt-3">Profissionais</h6>
                <h2>{{ $totalProfissionais }}</h2>
                <small class="text-danger">Acessar profissionais <i class="bi bi-arrow-right"></i></small>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-xl-3">
        <a href="{{ route('servicos.index') }}" class="text-decoration-none text-dark">
            <div class="stat-card">
                <i class="bi bi-briefcase stat-icon"></i>
                <h6 class="text-muted mt-3">Serviços</h6>
                <h2>{{ $totalServicos }}</h2>
                <small class="text-danger">Acessar serviços <i class="bi bi-arrow-right"></i></small>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-xl-3">
        <a href="{{ route('agendamentos.index') }}" class="text-decoration-none text-dark">
            <div class="stat-card">
                <i class="bi bi-calendar-check stat-icon"></i>
                <h6 class="text-muted mt-3">Agendamentos hoje</h6>
                <h2>{{ $agendamentosHoje }}</h2>
                <small class="text-danger">Acessar agenda <i class="bi bi-arrow-right"></i></small>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-xl-6">
        <div class="stat-card">
            <i class="bi bi-cash-stack stat-icon"></i>
            <h6 class="text-muted mt-3">Faturamento de hoje</h6>
            <h2>R$ {{ number_format($faturamentoHoje, 2, ',', '.') }}</h2>
            <small class="text-muted">Serviços concluídos</small>
        </div>
    </div>

    <div class="col-md-6 col-xl-6">
        <div class="stat-card">
            <i class="bi bi-graph-up-arrow stat-icon"></i>
            <h6 class="text-muted mt-3">Faturamento do mês</h6>
            <h2>R$ {{ number_format($faturamentoMes, 2, ',', '.') }}</h2>
            <small class="text-muted">Serviços concluídos neste mês</small>
        </div>
    </div>

</div>

<div class="stat-card mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="mb-1">Próximos agendamentos</h5>
            <small class="text-muted">Os próximos 5 horários da agenda.</small>
        </div>

        <a href="{{ route('agendamentos.index') }}" class="btn btn-primary btn-sm">
            Ver todos
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Horário</th>
                    <th>Cliente</th>
                    <th>Profissional</th>
                    <th>Serviço</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse($proximosAgendamentos as $agendamento)
                    <tr>
                        <td>{{ $agendamento->data->format('d/m/Y') }}</td>
                        <td>{{ substr($agendamento->hora, 0, 5) }}</td>
                        <td>{{ $agendamento->cliente->nome ?? 'Não informado' }}</td>
                        <td>{{ $agendamento->profissional->nome ?? 'Não informado' }}</td>
                        <td>{{ $agendamento->servico->nome ?? 'Não informado' }}</td>
                        <td>
                            @if($agendamento->status == 'concluido')
                                <span class="badge bg-success">Concluído</span>
                            @elseif($agendamento->status == 'confirmado')
                                <span class="badge bg-primary">Confirmado</span>
                            @else
                                <span class="badge bg-warning text-dark">Pendente</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Nenhum agendamento encontrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="stat-card mt-4">
    <h5>Bem-vindo ao AgendeJá!</h5>
    <p class="text-muted mb-0">
        Gerencie seus clientes, profissionais, serviços, horários e recebimentos.
    </p>
</div>

@endsection
