<div class="mb-3">
    <label class="form-label">Nome completo *</label>

    <input
        type="text"
        name="nome"
        class="form-control"
        value="{{ old('nome', $cliente->nome ?? '') }}"
        required
    >

    @error('nome')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">E-mail</label>

    <input
        type="email"
        name="email"
        class="form-control"
        value="{{ old('email', $cliente->email ?? '') }}"
    >

    @error('email')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Telefone / WhatsApp *</label>

    <input
        type="text"
        name="telefone"
        class="form-control"
        value="{{ old('telefone', $cliente->telefone ?? '') }}"
        required
    >

    @error('telefone')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Observações</label>

    <textarea
        name="observacoes"
        class="form-control"
        rows="4"
    >{{ old('observacoes', $cliente->observacoes ?? '') }}</textarea>

    @error('observacoes')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-check-circle"></i> Salvar cliente
    </button>

    <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
        Cancelar
    </a>
</div>