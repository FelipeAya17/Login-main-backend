<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tiendas extends Model{
    use HasFactory;
    public $incremeting = false;
    public $timestamps = false;
    protected $primaryKey = 'codigo_tienda';
    protected $table = 'tiendas';
}
