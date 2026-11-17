
@extends('layouts.app')

@section('title', 'Horários do profissional | AgendeJá')

@section('content')

@php
    $dias = [
        0 => 'Domingo',
        1 => 'Segunda-feira',
        2 => 'Terça-feira',
        3 => 'Quarta-feira',
        4 => 'Quinta-feira',
        5 => 'Sexta-feira',
        6 => 'Sábado',
    ];
@endphp

<div class="mb-4">
    <h3>Horários de atendimento</h3>
    <p class="text-muted">
        Configure os dias e horários de {{ $profissional->nome }}.
    </p>
</div>

<div class="stat-card">
    <form action="{{ route('profissionais.horarios.update', $profissional->id) }}"
          method="POST">

        @csrf
        @method('PUT')

        @foreach($dias as $dia => $nome)
            @php
                $config = $horarios->get($dia);

                $horaInicio = old(
                    "horarios.$dia.hora_inicio",
                    substr($config?->hora_inicio ?? '07:00:00', 0, 5)
                );

                $horaFim = old(
                    "horarios.$dia.hora_fim",
                    substr($config?->hora_fim ?? '19:00:00', 0, 5)
                );

                $semIntervalo = in_array($dia, [0, 6])
                    && in_array($horaFim, ['12:00', '13:00']);
            @endphp

            <div class="border rounded p-3 mb-3"
                 data-dia="{{ $dia }}">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">{{ $nome }}</h5>

                    <div>
                        <input type="hidden"
                               name="horarios[{{ $dia }}][ativo]"
                               value="0">

                        <label class="form-check-label">
                            <input type="checkbox"
                                   class="form-check-input"
                                   name="horarios[{{ $dia }}][ativo]"
                                   value="1"
                                   {{ old("horarios.$dia.ativo", $config?->ativo ?? false) ? 'checked' : '' }}>
                            Atende neste dia
                        </label>
                    </div>
                </div>

                <div class="row g-3">

                    <div class="col-md-3">
                        <label class="form-label">Abertura</label>
                        <input type="time"
                               name="horarios[{{ $dia }}][hora_inicio]"
                               class="form-control"
                               value="{{ $horaInicio }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Fechamento</label>
                        <input type="time"
                               name="horarios[{{ $dia }}][hora_fim]"
                               class="form-control hora-fim"
                               value="{{ $horaFim }}">
                    </div>

                    <div class="col-md-6 campos-intervalo"
                         style="{{ $semIntervalo ? 'display:none;' : '' }}">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Início do intervalo</label>
                                <input type="time"
                                       name="horarios[{{ $dia }}][intervalo_inicio]"
                                       class="form-control campo-intervalo"
                                       value="{{ old("horarios.$dia.intervalo_inicio", $config?->intervalo_inicio ? substr($config->intervalo_inicio, 0, 5) : '') }}"
                                       {{ $semIntervalo ? 'disabled' : '' }}>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Fim do intervalo</label>
                                <input type="time"
                                       name="horarios[{{ $dia }}][intervalo_fim]"
                                       class="form-control campo-intervalo"
                                       value="{{ old("horarios.$dia.intervalo_fim", $config?->intervalo_fim ? substr($config->intervalo_fim, 0, 5) : '') }}"
                                       {{ $semIntervalo ? 'disabled' : '' }}>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 aviso-sem-intervalo"
                         style="{{ $semIntervalo ? '' : 'display:none;' }}">

                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle"></i>
                            Sem intervalo neste horário.
                        </div>
                    </div>

                </div>
            </div>
        @endforeach

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $erro)
                        <li>{{ $erro }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                Salvar horários
            </button>

            <a href="{{ route('profissionais.index') }}"
               class="btn btn-secondary">
                Voltar
            </a>
        </div>

    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('[data-dia]').forEach(function (dia) {

        const numeroDia = Number(dia.dataset.dia);
        const fechamento = dia.querySelector('.hora-fim');
        const camposIntervalo = dia.querySelector('.campos-intervalo');
        const aviso = dia.querySelector('.aviso-sem-intervalo');
        const campos = dia.querySelectorAll('.campo-intervalo');

        function atualizarIntervalo() {

            const semIntervalo =
                [0, 6].includes(numeroDia) &&
                ['12:00', '13:00'].includes(fechamento.value);

            camposIntervalo.style.display = semIntervalo ? 'none' : '';
            aviso.style.display = semIntervalo ? '' : 'none';

            campos.forEach(function (campo) {
                campo.disabled = semIntervalo;

                if (semIntervalo) {
                    campo.value = '';
                }
            });
        }

        fechamento.addEventListener('change', atualizarIntervalo);

        atualizarIntervalo();
    });

});
</script>

@endsection
