<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recebimento extends Model
{
    protected $table = 'recebimentos';

    protected $fillable = [
        'descricao',
        'valor',
        'data_recebimento',
    ];

    protected function casts(): array
    {
        return [
            'valor' => 'decimal:2',
            'data_recebimento' => 'date',
        ];
    }
}
