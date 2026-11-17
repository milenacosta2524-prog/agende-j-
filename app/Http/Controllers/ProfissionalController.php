<?php

namespace App\Http\Controllers;

use App\Models\Profissional;
use Illuminate\Http\Request;

class ProfissionalController extends Controller
{
    // Listar profissionais
    public function index(Request $request)
    {
        $profissionais = Profissional::when($request->busca, function ($query, $busca) {
            $query->where(function ($q) use ($busca) {
                $q->where('nome', 'like', "%{$busca}%")
                  ->orWhere('especialidade', 'like', "%{$busca}%");
            });
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

        return view('profissionais.index', compact('profissionais'));
    }

    // Formulário de cadastro
    public function create()
    {
        return view('profissionais.create');
    }

    // Salvar profissional
    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'especialidade' => 'nullable|string|max:255',
            'ativo' => 'nullable|boolean',
        ]);

        $dados['ativo'] = $request->boolean('ativo');

        Profissional::create($dados);

        return redirect()
            ->route('profissionais.index')
            ->with('sucesso', 'Profissional cadastrado com sucesso!');
    }

    // Formulário de edição
    public function edit(Profissional $profissionai)
    {
        return view('profissionais.edit', [
            'profissional' => $profissionai
        ]);
    }

    // Atualizar profissional
    public function update(Request $request, Profissional $profissionai)
    {
        $dados = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'especialidade' => 'nullable|string|max:255',
            'ativo' => 'nullable|boolean',
        ]);

        $dados['ativo'] = $request->boolean('ativo');

        $profissionai->update($dados);

        return redirect()
            ->route('profissionais.index')
            ->with('sucesso', 'Profissional atualizado com sucesso!');
    }

    // Excluir profissional
    public function destroy(Profissional $profissionai)
    {
        $profissionai->delete();

        return redirect()
            ->route('profissionais.index')
            ->with('sucesso', 'Profissional excluído com sucesso!');
    }
}
