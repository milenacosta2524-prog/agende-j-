@extends('layouts.app')

@section('title', 'Serviços | AgendeJá')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>Serviços</h3>
        <p class="text-muted">Gerencie os serviços oferecidos.</p>
    </div>

    <a href="{{ route('servicos.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Novo serviço
    </a>
</div>

@if(session('sucesso'))
    <div class="alert alert-success">{{ session('sucesso') }}</div>
@endif

@if(session('erro'))
    <div class="alert alert-danger">{{ session('erro') }}</div>
@endif

<div class="stat-card">
    <form method="GET" action="{{ route('servicos.index') }}" class="mb-4">
        <div class="input-group">
            <input type="text" name="busca" class="form-control" placeholder="Pesquisar por nome ou descrição..." value="{{ request('busca') }}">
            <button class="btn btn-primary"><i class="bi bi-search"></i> Pesquisar</button>
            <a href="{{ route('servicos.index') }}" class="btn btn-outline-secondary">Limpar</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Preço</th>
                    <th>Duração</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($servicos as $servico)
                    <tr>
                        <td>{{ $servico->nome }}</td>
                        <td>{{ Str::limit($servico->descricao ?? 'Sem descrição', 40) }}</td>
                        <td>R$ {{ number_format($servico->preco, 2, ',', '.') }}</td>
                        <td>{{ $servico->duracao }} min</td>
                        <td>
                            @if($servico->ativo)
                                <span class="badge bg-success">Ativo</span>
                            @else
                                <span class="badge bg-secondary">Inativo</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('servicos.edit', $servico) }}" class="btn btn-sm btn-warning" title="Editar"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('servicos.destroy', $servico) }}" method="POST" class="d-inline" onsubmit="return confirm('Deseja realmente excluir este serviço?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" title="Excluir"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Nenhum serviço cadastrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $servicos->links('pagination::bootstrap-5') }}
</div>
@endsection
