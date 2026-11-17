
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('horarios_profissionais', function (Blueprint $table) {
            $table->id();

            $table->foreignId('profissional_id')
                ->constrained('profissionais')
                ->cascadeOnDelete();

            // 0 = Domingo, 6 = Sábado
            $table->unsignedTinyInteger('dia_semana');

            $table->time('hora_inicio')->default('07:00:00');
            $table->time('hora_fim')->default('19:00:00');

            $table->time('intervalo_inicio')->nullable();
            $table->time('intervalo_fim')->nullable();

            $table->boolean('ativo')->default(true);

            $table->timestamps();

            $table->unique(
                ['profissional_id', 'dia_semana'],
                'profissional_dia_unico'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horarios_profissionais');
    }
};
