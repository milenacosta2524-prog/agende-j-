<?php

namespace App\Http\Controllers;

use App\Models\Recebimento;
use Illuminate\Http\Request;

class RecebimentoController extends Controller
{
    // Histórico e filtros
    public function index(Request $request)
    {
        $hoje = today();

        $inicioMes = now()->startOfMonth()->toDateString();
        $fimMes = now()->endOfMonth()->toDateString();

        $dataInicio = $request->input('data_inicio');
        $dataFim = $request->input('data_fim');

        $consulta = Recebimento::query();

        if ($dataInicio) {
            $consulta->whereDate('data_recebimento', '>=', $dataInicio);
        }

        if ($dataFim) {
            $consulta->whereDate('data_recebimento', '<=', $dataFim);
        }

        $recebimentos = $consulta
            ->orderByDesc('data_recebimento')
            ->orderByDesc('id')
            ->get();

        $totalFiltrado = $recebimentos->sum('valor');

        $totalHoje = Recebimento::whereDate('data_recebimento', $hoje)
            ->sum('valor');

        $totalMes = Recebimento::whereBetween('data_recebimento', [
            $inicioMes,
            $fimMes
        ])->sum('valor');

        return view('recebimentos.index', compact(
            'recebimentos',
            'totalHoje',
            'totalMes',
            'dataInicio',
            'dataFim',
            'totalFiltrado'
        ));
    }

    // Formulário de cadastro
    public function create()
    {
        return view('recebimentos.create');
    }

    // Salvar recebimento
    public function store(Request $request)
    {
        $dados = $request->validate([
            'descricao' => ['required', 'string', 'max:255'],
            'valor' => ['required', 'numeric', 'min:0.01'],
            'data_recebimento' => ['required', 'date'],
        ]);

        Recebimento::create($dados);

        return redirect()
            ->route('recebimentos.index')
            ->with('success', 'Recebimento cadastrado com sucesso!');
    }

    // Formulário de edição
    public function edit(Recebimento $recebimento)
    {
        return view('recebimentos.edit', compact('recebimento'));
    }

    // Atualizar recebimento
    public function update(Request $request, Recebimento $recebimento)
    {
        $dados = $request->validate([
            'descricao' => ['required', 'string', 'max:255'],
            'valor' => ['required', 'numeric', 'min:0.01'],
            'data_recebimento' => ['required', 'date'],
        ]);

        $recebimento->update($dados);

        return redirect()
            ->route('recebimentos.index')
            ->with('success', 'Recebimento atualizado com sucesso!');
    }

    // Excluir recebimento
    public function destroy(Recebimento $recebimento)
    {
        $recebimento->delete();

        return redirect()
            ->route('recebimentos.index')
            ->with('success', 'Recebimento excluído com sucesso!');
    }

    // Relatório financeiro anual
    public function relatorio(Request $request)
    {
        $request->validate([
            'ano' => ['nullable', 'integer', 'min:2000', 'max:2100'],
        ]);

        $ano = $request->input('ano', now()->year);

        $recebimentos = Recebimento::whereYear('data_recebimento', $ano)
            ->get();

        $totalAno = $recebimentos->sum('valor');
        $quantidade = $recebimentos->count();

        $totaisPorMes = Recebimento::selectRaw(
            'MONTH(data_recebimento) as mes, SUM(valor) as total'
        )
            ->whereYear('data_recebimento', $ano)
            ->groupByRaw('MONTH(data_recebimento)')
            ->pluck('total', 'mes');

        $nomesMeses = [
            'Janeiro', 'Fevereiro', 'Março',
            'Abril', 'Maio', 'Junho',
            'Julho', 'Agosto', 'Setembro',
            'Outubro', 'Novembro', 'Dezembro'
        ];

        $dadosMensais = [];

        for ($mes = 1; $mes <= 12; $mes++) {
            $dadosMensais[] = (float) ($totaisPorMes[$mes] ?? 0);
        }

        return view('recebimentos.relatorio', compact(
            'ano',
            'totalAno',
            'quantidade',
            'nomesMeses',
            'dadosMensais'
        ));
    }

    // Exportar recebimentos para Excel (CSV)
    public function exportar(Request $request)
    {
        $dados = $request->validate([
            'data_inicio' => ['nullable', 'date'],
            'data_fim' => ['nullable', 'date', 'after_or_equal:data_inicio'],
        ]);

        $consulta = Recebimento::query();

        if (!empty($dados['data_inicio'])) {
            $consulta->whereDate(
                'data_recebimento',
                '>=',
                $dados['data_inicio']
            );
        }

        if (!empty($dados['data_fim'])) {
            $consulta->whereDate(
                'data_recebimento',
                '<=',
                $dados['data_fim']
            );
        }

        $recebimentos = $consulta
            ->orderBy('data_recebimento')
            ->get();

        return response()->streamDownload(function () use ($recebimentos) {
            $arquivo = fopen('php://output', 'w');

            // Compatibilidade com acentos no Excel
            fwrite($arquivo, "\xEF\xBB\xBF");

            fputcsv($arquivo, [
                'Data',
                'Descrição',
                'Valor recebido'
            ], ';', '"', '\\');

            foreach ($recebimentos as $recebimento) {
                fputcsv($arquivo, [
                    $recebimento->data_recebimento->format('d/m/Y'),
                    $recebimento->descricao,
                    number_format($recebimento->valor, 2, ',', '.')
                ], ';', '"', '\\');
            }

            fclose($arquivo);
        }, 'relatorio-financeiro.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
