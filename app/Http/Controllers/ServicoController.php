<?php

namespace App\Http\Controllers;

use App\Models\Servico;
use Illuminate\Http\Request;

class ServicoController extends Controller
{
    public function index(Request $request)
    {
        $servicos = Servico::when($request->busca, function ($query, $busca) {
            $query->where(function ($q) use ($busca) {
                $q->where('nome', 'like', "%{$busca}%")
                  ->orWhere('descricao', 'like', "%{$busca}%");
            });
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

        return view('servicos.index', compact('servicos'));
    }

    public function create()
    {
        return view('servicos.create');
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'preco' => 'required|numeric|min:0',
            'duracao' => 'required|integer|min:1',
            'ativo' => 'nullable|boolean',
        ]);

        $dados['ativo'] = $request->boolean('ativo');

        Servico::create($dados);

        return redirect()
            ->route('servicos.index')
            ->with('sucesso', 'Serviço cadastrado com sucesso!');
    }

    public function edit(Servico $servico)
    {
        return view('servicos.edit', compact('servico'));
    }

    public function update(Request $request, Servico $servico)
    {
        $dados = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'preco' => 'required|numeric|min:0',
            'duracao' => 'required|integer|min:1',
            'ativo' => 'nullable|boolean',
        ]);

        $dados['ativo'] = $request->boolean('ativo');

        $servico->update($dados);

        return redirect()
            ->route('servicos.index')
            ->with('sucesso', 'Serviço atualizado com sucesso!');
    }

    public function destroy(Servico $servico)
    {
        $servico->delete();

        return redirect()
            ->route('servicos.index')
            ->with('sucesso', 'Serviço excluído com sucesso!');
    }
}
