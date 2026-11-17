@extends('layouts.app')

@section('title', 'Clientes | AgendeJá')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>Clientes</h3>
        <p class="text-muted">Gerencie seus clientes cadastrados.</p>
    </div>

    <a href="{{ route('clientes.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Novo cliente
    </a>
</div>

@if(session('sucesso'))
    <div class="alert alert-success">
        {{ session('sucesso') }}
    </div>
@endif

@if(session('erro'))
    <div class="alert alert-danger">
        {{ session('erro') }}
    </div>
@endif

<div class="stat-card">

    <form method="GET" action="{{ route('clientes.index') }}" class="mb-4">
        <div class="input-group">
            <input
                type="text"
                name="busca"
                class="form-control"
                placeholder="Pesquisar por nome ou telefone..."
                value="{{ request('busca') }}"
            >

            <button class="btn btn-primary">
                <i class="bi bi-search"></i> Pesquisar
            </button>

            <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">
                Limpar
            </a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Telefone</th>
                    <th>Cadastro</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                @forelse($clientes as $cliente)
                    <tr>
                        <td>{{ $cliente->nome }}</td>
                        <td>{{ $cliente->email ?? 'Não informado' }}</td>
                        <td>{{ $cliente->telefone }}</td>
                        <td>{{ $cliente->created_at->format('d/m/Y') }}</td>

                        <td>
                            <a
                                href="{{ route('clientes.edit', $cliente) }}"
                                class="btn btn-sm btn-warning"
                                title="Editar"
                            >
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form
                                action="{{ route('clientes.destroy', $cliente) }}"
                                method="POST"
                                class="d-inline"
                                onsubmit="return confirm('Deseja realmente excluir este cliente?')"
                            >
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
                        <td colspan="5" class="text-center text-muted py-4">
                            Nenhum cliente cadastrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $clientes->links('pagination::bootstrap-5') }}

</div>

@endsection