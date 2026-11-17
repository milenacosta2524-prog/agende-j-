
@extends('layouts.app')

@section('title', 'Agenda | AgendeJá')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h3 class="mb-1">Agenda de atendimentos</h3>
        <p class="text-muted mb-0">Organize os horários por profissional.</p>
    </div>

    <a href="{{ route('agendamentos.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Novo agendamento
    </a>
</div>

@if(session('sucesso'))
    <div class="alert alert-success">{{ session('sucesso') }}</div>
@endif

@if(session('erro'))
    <div class="alert alert-danger">{{ session('erro') }}</div>
@endif

<div class="stat-card mb-4">
    <form method="GET" action="{{ route('agendamentos.index') }}">
        <div class="row g-3 align-items-end">

            <div class="col-md-3">
                <label class="form-label">Data da agenda</label>
                <input
                    type="date"
                    name="data"
                    class="form-control"
                    value="{{ $dataSelecionada }}"
                >
            </div>

            <div class="col-md-3">
                <label class="form-label">Profissional</label>
                <select name="profissional_id" class="form-select">
                    <option value="">Todos os profissionais</option>

                    @foreach($profissionais as $profissional)
                        <option
                            value="{{ $profissional->id }}"
                            {{ request('profissional_id') == $profissional->id ? 'selected' : '' }}
                        >
                            {{ $profissional->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Cliente</label>
                <input
                    type="text"
                    name="busca"
                    class="form-control"
                    placeholder="Nome do cliente"
                    value="{{ request('busca') }}"
                >
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Filtrar
                </button>

                <a href="{{ route('agendamentos.index') }}"
                   class="btn btn-outline-secondary">
                    Limpar
                </a>
            </div>

        </div>
    </form>
</div>

<div class="mb-3">
    <h5>
        <i class="bi bi-calendar-date"></i>
        Agenda do dia: {{ \Carbon\Carbon::parse($dataSelecionada)->format('d/m/Y') }}
    </h5>
    <span class="text-muted">
        {{ $agendamentos->count() }}
        {{ $agendamentos->count() == 1 ? 'atendimento encontrado' : 'atendimentos encontrados' }}
    </span>
</div>

@forelse($profissionais as $profissional)

    @php
        $itens = $agendamentos->where('profissional_id', $profissional->id);
    @endphp

    @if($itens->isNotEmpty() || !request('profissional_id'))

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="bi bi-person-badge text-primary"></i>
                    {{ $profissional->nome }}

                    @if($profissional->especialidade)
                        <small class="text-muted">
                            — {{ $profissional->especialidade }}
                        </small>
                    @endif
                </h5>
            </div>

            <div class="card-body">

                @forelse($itens as $agendamento)

                    <div class="border rounded-3 p-3 mb-3">
                        <div class="row align-items-center g-3">

                            <div class="col-md-2">
                                <div class="text-primary fw-bold fs-5">
                                    <i class="bi bi-clock"></i>
                                    {{ substr($agendamento->hora, 0, 5) }}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="fw-bold">
                                    {{ $agendamento->cliente->nome ?? 'Cliente não encontrado' }}
                                </div>
                                <small class="text-muted">
                                    {{ $agendamento->cliente->telefone ?? '' }}
                                </small>
                            </div>

                            <div class="col-md-3">
                                <div>{{ $agendamento->servico->nome ?? 'Serviço não encontrado' }}</div>
                                <small class="text-muted">Serviço agendado</small>
                            </div>

                            <div class="col-md-2">
                                @php
                                    $status = $agendamento->status ?? 'pendente';

                                    $cores = [
                                        'pendente' => 'warning',
                                        'confirmado' => 'success',
                                        'concluido' => 'primary',
                                        'cancelado' => 'danger',
                                    ];
                                @endphp

                                <span class="badge bg-{{ $cores[$status] ?? 'secondary' }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </div>

                            <div class="col-md-2 text-md-end">
                                <a
                                    href="{{ route('agendamentos.edit', $agendamento) }}"
                                    class="btn btn-sm btn-warning"
                                    title="Editar"
                                >
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form
                                    action="{{ route('agendamentos.destroy', $agendamento) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Deseja realmente excluir este agendamento?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger" title="Excluir">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>

                @empty
                    <p class="text-muted mb-0">
                        Nenhum atendimento marcado para este profissional nesta data.
                    </p>
                @endforelse

            </div>
        </div>

    @endif

@empty
    <div class="alert alert-info">
        Nenhum profissional cadastrado.
    </div>
@endforelse

@if($agendamentos->isEmpty())
    <div class="alert alert-info">
        Nenhum agendamento encontrado para os filtros selecionados.
    </div>
@endif

@endsection
