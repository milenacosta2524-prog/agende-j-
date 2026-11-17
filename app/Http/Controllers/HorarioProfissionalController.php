<?php

namespace App\Http\Controllers;

use App\Models\Profissional;
use Illuminate\Http\Request;

class HorarioProfissionalController extends Controller
{
    // Exibir os horários do profissional
    public function edit(Profissional $profissional)
    {
        $horarios = $profissional->horarios()
            ->get()
            ->keyBy('dia_semana');

        return view('horarios-profissionais.edit', compact(
            'profissional',
            'horarios'
        ));
    }

    // Salvar os horários
    public function update(Request $request, Profissional $profissional)
    {
        $dados = $request->validate([
            'horarios' => 'required|array',
            'horarios.*.ativo' => 'required|boolean',
            'horarios.*.hora_inicio' => 'nullable|date_format:H:i',
            'horarios.*.hora_fim' => 'nullable|date_format:H:i',
            'horarios.*.intervalo_inicio' => 'nullable|date_format:H:i',
            'horarios.*.intervalo_fim' => 'nullable|date_format:H:i',
        ]);

        foreach ($dados['horarios'] as $dia => $horario) {
            $ativo = (bool) $horario['ativo'];

            $inicio = $horario['hora_inicio'] ?? '07:00';
            $fim = $horario['hora_fim'] ?? '19:00';

            if ($ativo && $inicio >= $fim) {
                return back()
                    ->withErrors([
                        "horarios.$dia.hora_fim" =>
                            'O horário de encerramento deve ser depois do início.'
                    ])
                    ->withInput();
            }

            $profissional->horarios()->updateOrCreate(
                ['dia_semana' => $dia],
                [
                    'ativo' => $ativo,
                    'hora_inicio' => $inicio,
                    'hora_fim' => $fim,
                    'intervalo_inicio' => $horario['intervalo_inicio'] ?? null,
                    'intervalo_fim' => $horario['intervalo_fim'] ?? null,
                ]
            );
        }

        return redirect()
            ->route('profissionais.index')
            ->with('success', 'Horários do profissional atualizados com sucesso!');
    }
}
