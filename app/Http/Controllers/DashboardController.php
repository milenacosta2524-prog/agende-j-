<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Profissional;
use App\Models\Servico;
use App\Models\Agendamento;
use App\Models\Recebimento;

class DashboardController extends Controller
{
    public function index()
    {
        $hoje = today();
        $inicioMes = now()->startOfMonth();
        $fimMes = now()->endOfMonth();

        // Quantidade de agendamentos de hoje
        $agendamentosHoje = Agendamento::whereDate('data', $hoje)
            ->where('status', '!=', 'cancelado')
            ->count();

        // Valores reais cadastrados no Financeiro
        $faturamentoHoje = Recebimento::whereDate(
            'data_recebimento',
            $hoje
        )->sum('valor');

        $faturamentoMes = Recebimento::whereBetween(
            'data_recebimento',
            [
                $inicioMes->toDateString(),
                $fimMes->toDateString()
            ]
        )->sum('valor');

        // Próximos agendamentos
        $proximosAgendamentos = Agendamento::with([
                'cliente',
                'profissional',
                'servico'
            ])
            ->whereDate('data', '>=', $hoje)
            ->where('status', '!=', 'cancelado')
            ->orderBy('data')
            ->orderBy('hora')
            ->limit(5)
            ->get();

        return view('dashboard', [
            'totalClientes' => Cliente::count(),
            'totalProfissionais' => Profissional::count(),
            'totalServicos' => Servico::count(),
            'agendamentosHoje' => $agendamentosHoje,
            'faturamentoHoje' => $faturamentoHoje,
            'faturamentoMes' => $faturamentoMes,
            'proximosAgendamentos' => $proximosAgendamentos,
        ]);
    }
}
