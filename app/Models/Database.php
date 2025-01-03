<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Database extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'domain_id', 
        'user_id',
        'usage', // Adiciona 'usage' ao fillable para permitir preenchimento
    ];
    
}
