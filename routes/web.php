
<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProfissionalController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\HorarioProfissionalController;
use App\Http\Controllers\RecebimentoController;
use App\Http\Controllers\DashboardController;

// Página inicial
Route::redirect('/', '/dashboard')->name('home');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

// Clientes
Route::resource('clientes', ClienteController::class)
    ->except(['show']);

// Profissionais
Route::resource('profissionais', ProfissionalController::class)
    ->except(['show']);

// Serviços
Route::resource('servicos', ServicoController::class)
    ->except(['show']);

// Agendamentos
Route::resource('agendamentos', AgendamentoController::class)
    ->except(['show']);

// Buscar horários disponíveis
Route::get(
    '/profissionais/{profissional}/horarios-disponiveis',
    [AgendamentoController::class, 'horariosDisponiveis']
)->name('agendamentos.horarios-disponiveis');

// Horários dos profissionais
Route::get(
    '/profissionais/{profissional}/horarios',
    [HorarioProfissionalController::class, 'edit']
)->name('profissionais.horarios.edit');

Route::put(
    '/profissionais/{profissional}/horarios',
    [HorarioProfissionalController::class, 'update']
)->name('profissionais.horarios.update');

// Financeiro
Route::resource('recebimentos', RecebimentoController::class)
    ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

// Relatório financeiro
Route::get(
    '/financeiro/relatorio',
    [RecebimentoController::class, 'relatorio']
)->name('recebimentos.relatorio');

// Exportar relatório financeiro
Route::get(
    '/financeiro/exportar',
    [RecebimentoController::class, 'exportar']
)->name('recebimentos.exportar');

