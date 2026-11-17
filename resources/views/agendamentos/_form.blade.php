
<div class="row">
    @if(isset($agendamento))
        <div class="col-md-6 mb-3">
            <label class="form-label">Cliente *</label>
            <select name="cliente_id" class="form-control" required>
                <option value="">Selecione</option>
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}"
                        {{ old('cliente_id', $agendamento->cliente_id) == $cliente->id ? 'selected' : '' }}>
                        {{ $cliente->nome }}
                    </option>
                @endforeach
            </select>
            @error('cliente_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    @else
        <div class="col-md-4 mb-3">
            <label class="form-label">Nome do cliente *</label>
            <input type="text" name="cliente_nome" class="form-control"
                   value="{{ old('cliente_nome') }}" required>
            @error('cliente_nome')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Telefone do cliente *</label>
            <input type="text" name="cliente_telefone" class="form-control"
                   value="{{ old('cliente_telefone') }}" required>
            @error('cliente_telefone')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    @endif
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Profissional responsável *</label>

        <select name="profissional_id" id="profissional_id"
                class="form-control" required>
            <option value="">Selecione o profissional</option>

            @foreach($profissionais as $profissional)
                <option value="{{ $profissional->id }}"
                    {{ old('profissional_id', $agendamento->profissional_id ?? '') == $profissional->id ? 'selected' : '' }}>
                    {{ $profissional->nome }}
                    @if($profissional->especialidade)
                        - {{ $profissional->especialidade }}
                    @endif
                </option>
            @endforeach
        </select>

        @error('profissional_id')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Serviço *</label>

        <select name="servico_id" id="servico_id"
                class="form-control" required>
            <option value="">Selecione</option>

            @foreach($servicos as $servico)
                <option value="{{ $servico->id }}"
                    {{ old('servico_id', $agendamento->servico_id ?? '') == $servico->id ? 'selected' : '' }}>
                    {{ $servico->nome }}
                </option>
            @endforeach
        </select>

        @error('servico_id')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Status</label>

        <select name="status" class="form-control">
            <option value="pendente" {{ old('status', $agendamento->status ?? 'pendente') == 'pendente' ? 'selected' : '' }}>Pendente</option>
            <option value="confirmado" {{ old('status', $agendamento->status ?? 'pendente') == 'confirmado' ? 'selected' : '' }}>Confirmado</option>
            <option value="concluido" {{ old('status', $agendamento->status ?? 'pendente') == 'concluido' ? 'selected' : '' }}>Concluído</option>
            <option value="cancelado" {{ old('status', $agendamento->status ?? 'pendente') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
        </select>

        @error('status')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Data *</label>

        <input type="date" name="data" id="data"
               class="form-control"
               value="{{ old('data', isset($agendamento) ? $agendamento->data->format('Y-m-d') : '') }}"
               required>

        @error('data')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Hora *</label>

        <select name="hora" id="hora" class="form-control" required disabled>
            <option value="">Selecione profissional, serviço e data</option>
        </select>

        <small id="mensagem-horarios" class="text-muted">
            Escolha o profissional, o serviço e a data para consultar os horários.
        </small>

        @error('hora')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Observações</label>

    <textarea name="observacoes" class="form-control" rows="4">{{ old('observacoes', $agendamento->observacoes ?? '') }}</textarea>

    @error('observacoes')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-check-circle"></i> Salvar agendamento
    </button>

    <a href="{{ route('agendamentos.index') }}" class="btn btn-secondary">
        Cancelar
    </a>
</div>

<div id="config-horarios"
     data-url="{{ route('agendamentos.horarios-disponiveis', ['profissional' => 'PROFISSIONAL_ID']) }}"
     data-agendamento-id="{{ $agendamento->id ?? '' }}"
     data-hora-atual="{{ substr((string) old('hora', $agendamento->hora ?? ''), 0, 5) }}">
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const profissional = document.getElementById('profissional_id');
    const servico = document.getElementById('servico_id');
    const data = document.getElementById('data');
    const hora = document.getElementById('hora');
    const mensagem = document.getElementById('mensagem-horarios');
    const config = document.getElementById('config-horarios');

    const agendamentoId = config.dataset.agendamentoId;
    const horaAtual = config.dataset.horaAtual;
    const urlBase = config.dataset.url;

    async function carregarHorarios() {
        const profissionalId = profissional.value;
        const servicoId = servico.value;
        const dataSelecionada = data.value;

        hora.innerHTML = '<option value="">Selecione um horário</option>';
        hora.disabled = true;

        if (!profissionalId || !servicoId || !dataSelecionada) {
            mensagem.textContent = 'Escolha o profissional, o serviço e a data para consultar os horários.';
            return;
        }

        mensagem.textContent = 'Buscando horários disponíveis...';

        try {
            let url = urlBase.replace('PROFISSIONAL_ID', profissionalId);

            const parametros = new URLSearchParams({
                data: dataSelecionada,
                servico_id: servicoId
            });

            if (agendamentoId) {
                parametros.append('agendamento_id', agendamentoId);
            }

            const resposta = await fetch(url + '?' + parametros.toString());

            if (!resposta.ok) {
                throw new Error('Não foi possível consultar os horários.');
            }

            const horarios = await resposta.json();

            hora.innerHTML = '<option value="">Selecione um horário</option>';

            if (horarios.length === 0) {
                mensagem.textContent = 'Não existem horários disponíveis para essa data.';
                return;
            }

            horarios.forEach(function (horario) {
                const opcao = document.createElement('option');
                opcao.value = horario;
                opcao.textContent = horario;

                if (horario === horaAtual) {
                    opcao.selected = true;
                }

                hora.appendChild(opcao);
            });

            hora.disabled = false;
            mensagem.textContent = 'Selecione um dos horários disponíveis.';

        } catch (erro) {
            mensagem.textContent = 'Erro ao carregar horários. Tente novamente.';
            console.error(erro);
        }
    }

    profissional.addEventListener('change', carregarHorarios);
    servico.addEventListener('change', carregarHorarios);
    data.addEventListener('change', carregarHorarios);

    // Carrega automaticamente ao abrir a edição ou quando os campos já estão preenchidos.
    carregarHorarios();
});
</script>
