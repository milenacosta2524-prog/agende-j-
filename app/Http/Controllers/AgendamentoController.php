<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Cliente;
use App\Models\Profissional;
use App\Models\Servico;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AgendamentoController extends Controller
{
    // CONSULTAR HORÁRIOS DISPONÍVEIS
    public function horariosDisponiveis(Request $request, Profissional $profissional)
    {
        $dados = $request->validate([
            'data' => 'required|date',
            'servico_id' => 'required|exists:servicos,id',
            'agendamento_id' => 'nullable|exists:agendamentos,id',
        ]);

        $servico = Servico::findOrFail($dados['servico_id']);
        $duracao = (int) $servico->duracao;

        if ($duracao <= 0) {
            return response()->json([]);
        }

        $data = Carbon::parse($dados['data']);
        $diaSemana = $data->dayOfWeek;

        $expediente = $profissional->horarios()
            ->where('dia_semana', $diaSemana)
            ->where('ativo', true)
            ->first();

        if (!$expediente) {
            return response()->json([]);
        }

        $agendamentos = Agendamento::with('servico')
            ->where('profissional_id', $profissional->id)
            ->whereDate('data', $dados['data'])
            ->where(function ($query) {
                $query->whereNull('status')
                    ->orWhere('status', '!=', 'cancelado');
            })
            ->when($request->filled('agendamento_id'), function ($query) use ($dados) {
                $query->where('id', '!=', $dados['agendamento_id']);
            })
            ->get();

        $horarios = [];

        $inicio = Carbon::parse($dados['data'] . ' ' . $expediente->hora_inicio);
        $fim = Carbon::parse($dados['data'] . ' ' . $expediente->hora_fim);

        $intervaloInicio = $expediente->intervalo_inicio
            ? Carbon::parse($dados['data'] . ' ' . $expediente->intervalo_inicio)
            : null;

        $intervaloFim = $expediente->intervalo_fim
            ? Carbon::parse($dados['data'] . ' ' . $expediente->intervalo_fim)
            : null;

        for (
            $horario = $inicio->copy();
            $horario->copy()->addMinutes($duracao)->lte($fim);
            $horario->addMinutes(30)
        ) {
            $termino = $horario->copy()->addMinutes($duracao);

            if (
                $intervaloInicio &&
                $intervaloFim &&
                $horario->lt($intervaloFim) &&
                $termino->gt($intervaloInicio)
            ) {
                continue;
            }

            $ocupado = $agendamentos->contains(function ($agendamento) use ($dados, $horario, $termino) {
                if (!$agendamento->servico) {
                    return false;
                }

                $inicioOcupado = Carbon::parse(
                    $dados['data'] . ' ' . $agendamento->hora
                );

                $fimOcupado = $inicioOcupado->copy()->addMinutes(
                    (int) $agendamento->servico->duracao
                );

                return $horario->lt($fimOcupado)
                    && $termino->gt($inicioOcupado);
            });

            if (!$ocupado) {
                $horarios[] = $horario->format('H:i');
            }
        }

        return response()->json($horarios);
    }

    // VALIDAR EXPEDIENTE E CONFLITOS
    private function validarDisponibilidade(array $dados, ?int $agendamentoId = null): ?string
    {
        $profissional = Profissional::findOrFail($dados['profissional_id']);
        $servico = Servico::findOrFail($dados['servico_id']);

        $duracao = (int) $servico->duracao;

        if ($duracao <= 0) {
            return 'A duração do serviço precisa ser maior que zero.';
        }

        $data = Carbon::parse($dados['data']);
        $diaSemana = $data->dayOfWeek;

        // Verificar expediente do profissional
        $expediente = $profissional->horarios()
            ->where('dia_semana', $diaSemana)
            ->where('ativo', true)
            ->first();

        if (!$expediente) {
            return 'Este profissional não trabalha no dia selecionado.';
        }

        $inicioExpediente = Carbon::parse(
            $dados['data'] . ' ' . $expediente->hora_inicio
        );

        $fimExpediente = Carbon::parse(
            $dados['data'] . ' ' . $expediente->hora_fim
        );

        $inicio = Carbon::parse(
            $dados['data'] . ' ' . $dados['hora']
        );

        $fim = $inicio->copy()->addMinutes($duracao);

        // Verificar se o horário está dentro do expediente
        if (
            $inicio->lt($inicioExpediente) ||
            $fim->gt($fimExpediente)
        ) {
            return 'O horário escolhido está fora do expediente do profissional ou ultrapassa o fechamento.';
        }

        // Verificar intervalo
        if ($expediente->intervalo_inicio && $expediente->intervalo_fim) {
            $intervaloInicio = Carbon::parse(
                $dados['data'] . ' ' . $expediente->intervalo_inicio
            );

            $intervaloFim = Carbon::parse(
                $dados['data'] . ' ' . $expediente->intervalo_fim
            );

            if (
                $inicio->lt($intervaloFim) &&
                $fim->gt($intervaloInicio)
            ) {
                return 'O horário escolhido coincide com o intervalo do profissional.';
            }
        }

        // Verificar conflitos com outros agendamentos
        if (($dados['status'] ?? 'pendente') !== 'cancelado') {
            $agendamentos = Agendamento::with('servico')
                ->where('profissional_id', $dados['profissional_id'])
                ->whereDate('data', $dados['data'])
                ->where(function ($query) {
                    $query->whereNull('status')
                        ->orWhere('status', '!=', 'cancelado');
                })
                ->when($agendamentoId, function ($query) use ($agendamentoId) {
                    $query->where('id', '!=', $agendamentoId);
                })
                ->get();

            foreach ($agendamentos as $outro) {
                if (!$outro->servico) {
                    continue;
                }

                $inicioOutro = Carbon::parse(
                    $dados['data'] . ' ' . $outro->hora
                );

                $fimOutro = $inicioOutro->copy()->addMinutes(
                    (int) $outro->servico->duracao
                );

                if (
                    $inicio->lt($fimOutro) &&
                    $fim->gt($inicioOutro)
                ) {
                    return 'Este horário entra em conflito com outro agendamento deste profissional.';
                }
            }
        }

        return null;
    }

    // LISTAR AGENDAMENTOS
    public function index(Request $request)
    {
        $dataSelecionada = $request->input('data', now()->toDateString());

        $agendamentos = Agendamento::with(['cliente', 'profissional', 'servico'])
            ->whereDate('data', $dataSelecionada)
            ->when($request->filled('profissional_id'), function ($query) use ($request) {
                $query->where('profissional_id', $request->profissional_id);
            })
            ->when($request->busca, function ($query, $busca) {
                $query->whereHas('cliente', function ($q) use ($busca) {
                    $q->where('nome', 'like', "%{$busca}%");
                });
            })
            ->orderBy('hora')
            ->get();

        $profissionais = Profissional::orderBy('nome')->get();

        return view('agendamentos.index', compact(
            'agendamentos',
            'profissionais',
            'dataSelecionada'
        ));
    }

    // FORMULÁRIO DE NOVO AGENDAMENTO
    public function create()
    {
        return view('agendamentos.create', [
            'clientes' => Cliente::orderBy('nome')->get(),
            'profissionais' => Profissional::orderBy('nome')->get(),
            'servicos' => Servico::where('ativo', true)->orderBy('nome')->get(),
        ]);
    }

    // SALVAR AGENDAMENTO
    public function store(Request $request)
    {
        $dados = $request->validate([
            'cliente_nome' => 'required|string|max:255',
            'cliente_telefone' => 'required|string|max:20',
            'profissional_id' => 'required|exists:profissionais,id',
            'servico_id' => 'required|exists:servicos,id',
            'data' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'status' => 'nullable|string|max:50',
            'observacoes' => 'nullable|string',
        ]);

        $erro = $this->validarDisponibilidade($dados);

        if ($erro) {
            return back()
                ->withErrors(['hora' => $erro])
                ->withInput();
        }

        $cliente = Cliente::firstOrCreate([
            'nome' => $dados['cliente_nome'],
            'telefone' => $dados['cliente_telefone'],
        ]);

        unset($dados['cliente_nome'], $dados['cliente_telefone']);

        $dados['cliente_id'] = $cliente->id;

        Agendamento::create($dados);

        return redirect()
            ->route('agendamentos.index')
            ->with('sucesso', 'Agendamento cadastrado com sucesso!');
    }

    // EDITAR AGENDAMENTO
    public function edit(Agendamento $agendamento)
    {
        return view('agendamentos.edit', [
            'agendamento' => $agendamento,
            'clientes' => Cliente::orderBy('nome')->get(),
            'profissionais' => Profissional::orderBy('nome')->get(),
            'servicos' => Servico::where('ativo', true)->orderBy('nome')->get(),
        ]);
    }

    // ATUALIZAR AGENDAMENTO
    public function update(Request $request, Agendamento $agendamento)
    {
        $dados = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'profissional_id' => 'required|exists:profissionais,id',
            'servico_id' => 'required|exists:servicos,id',
            'data' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'status' => 'nullable|string|max:50',
            'observacoes' => 'nullable|string',
        ]);

        $erro = $this->validarDisponibilidade($dados, $agendamento->id);

        if ($erro) {
            return back()
                ->withErrors(['hora' => $erro])
                ->withInput();
        }

        $agendamento->update($dados);

        return redirect()
            ->route('agendamentos.index')
            ->with('sucesso', 'Agendamento atualizado com sucesso!');
    }

    // EXCLUIR AGENDAMENTO
    public function destroy(Agendamento $agendamento)
    {
        $agendamento->delete();

        return redirect()
            ->route('agendamentos.index')
            ->with('sucesso', 'Agendamento excluído com sucesso!');
    }
}
