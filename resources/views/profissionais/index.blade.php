
@extends('layouts.app')

@section('title', 'Profissionais | AgendeJá')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>Profissionais</h3>
        <p class="text-muted">Gerencie os profissionais da sua agenda.</p>
    </div>

    <a href="{{ route('profissionais.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Novo profissional
    </a>
</div>

@if(session('sucesso'))
    <div class="alert alert-success">{{ session('sucesso') }}</div>
@endif

@if(session('erro'))
    <div class="alert alert-danger">{{ session('erro') }}</div>
@endif

<div class="stat-card">
    <form method="GET" action="{{ route('profissionais.index') }}" class="mb-4">
        <div class="input-group">
            <input type="text" name="busca" class="form-control"
                   placeholder="Pesquisar por nome ou especialidade..."
                   value="{{ request('busca') }}">
            <button class="btn btn-primary">
                <i class="bi bi-search"></i> Pesquisar
            </button>
            <a href="{{ route('profissionais.index') }}"
               class="btn btn-outline-secondary">Limpar</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Telefone</th>
                    <th>Especialidade</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                @forelse($profissionais as $profissional)
                    <tr>
                        <td>{{ $profissional->nome }}</td>
                        <td>{{ $profissional->email ?? 'Não informado' }}</td>
                        <td>{{ $profissional->telefone ?? 'Não informado' }}</td>
                        <td>{{ $profissional->especialidade ?? 'Não informado' }}</td>

                        <td>
                            @if($profissional->ativo)
                                <span class="badge bg-success">Ativo</span>
                            @else
                                <span class="badge bg-secondary">Inativo</span>
                            @endif
                        </td>

                        <td class="text-nowrap">

                            <a href="{{ route('profissionais.horarios.edit', $profissional->id) }}"
                               class="btn btn-sm btn-info text-white"
                               title="Configurar horários">
                                <i class="bi bi-clock"></i> Horários
                            </a>

                            <a href="{{ route('profissionais.edit', $profissional) }}"
                               class="btn btn-sm btn-warning"
                               title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form action="{{ route('profissionais.destroy', $profissional) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Deseja realmente excluir este profissional?')">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-sm btn-danger" title="Excluir">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Nenhum profissional cadastrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $profissionais->links('pagination::bootstrap-5') }}
</div>
@endsection
