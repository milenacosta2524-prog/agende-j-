<div class="mb-3">
    <label class="form-label">Nome completo *</label>
    <input type="text" name="nome" class="form-control" value="{{ old('nome', $profissional->nome ?? '') }}" required>
    @error('nome')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">E-mail</label>
    <input type="email" name="email" class="form-control" value="{{ old('email', $profissional->email ?? '') }}">
    @error('email')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Telefone</label>
    <input type="text" name="telefone" class="form-control" value="{{ old('telefone', $profissional->telefone ?? '') }}">
    @error('telefone')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Especialidade</label>
    <input type="text" name="especialidade" class="form-control" value="{{ old('especialidade', $profissional->especialidade ?? '') }}">
    @error('especialidade')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3 form-check">
    <input type="checkbox" name="ativo" class="form-check-input" value="1" {{ old('ativo', $profissional->ativo ?? true) ? 'checked' : '' }}>
    <label class="form-check-label">Profissional ativo</label>
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Salvar profissional</button>
    <a href="{{ route('profissionais.index') }}" class="btn btn-secondary">Cancelar</a>
</div>
