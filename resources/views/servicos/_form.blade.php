<div class="mb-3">
    <label class="form-label">Nome *</label>
    <input type="text" name="nome" class="form-control" value="{{ old('nome', $servico->nome ?? '') }}" required>
    @error('nome')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Descrição</label>
    <textarea name="descricao" class="form-control" rows="4">{{ old('descricao', $servico->descricao ?? '') }}</textarea>
    @error('descricao')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Preço *</label>
        <input type="number" name="preco" class="form-control" min="0" step="0.01" value="{{ old('preco', $servico->preco ?? '') }}" required>
        @error('preco')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Duração (minutos) *</label>
        <input type="number" name="duracao" class="form-control" min="1" value="{{ old('duracao', $servico->duracao ?? '') }}" required>
        @error('duracao')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mb-3 form-check">
    <input type="checkbox" name="ativo" class="form-check-input" value="1" {{ old('ativo', $servico->ativo ?? true) ? 'checked' : '' }}>
    <label class="form-check-label">Serviço ativo</label>
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Salvar serviço</button>
    <a href="{{ route('servicos.index') }}" class="btn btn-secondary">Cancelar</a>
</div>
