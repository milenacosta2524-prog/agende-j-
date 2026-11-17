<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    // Listar e pesquisar clientes
    public function index(Request $request)
    {
        $clientes = Cliente::when($request->busca, function ($query, $busca) {
            $query->where(function ($q) use ($busca) {
                $q->where('nome', 'like', "%{$busca}%")
                  ->orWhere('telefone', 'like', "%{$busca}%");
            });
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

        return view('clientes.index', compact('clientes'));
    }

    // Abrir formulário de cadastro
    public function create()
    {
        return view('clientes.create');
    }

    // Salvar novo cliente
    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefone' => 'required|string|max:20',
            'observacoes' => 'nullable|string',
        ]);

        Cliente::create($dados);

        return redirect()
            ->route('clientes.index')
            ->with('sucesso', 'Cliente cadastrado com sucesso!');
    }

    // Abrir formulário de edição
    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    // Atualizar cliente
    public function update(Request $request, Cliente $cliente)
    {
        $dados = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefone' => 'required|string|max:20',
            'observacoes' => 'nullable|string',
        ]);

        $cliente->update($dados);

        return redirect()
            ->route('clientes.index')
            ->with('sucesso', 'Cliente atualizado com sucesso!');
    }

    // Excluir cliente
    public function destroy(Cliente $cliente)
    {
        if ($cliente->agendamentos()->exists()) {
            return back()->with(
                'erro',
                'Este cliente possui agendamentos e não pode ser excluído.'
            );
        }

        $cliente->delete();

        return redirect()
            ->route('clientes.index')
            ->with('sucesso', 'Cliente excluído com sucesso!');
    }
}
