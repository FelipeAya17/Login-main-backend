<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class TercerosDirecciones extends Model{
    use HasFactory;
    protected $guarded = [];
	public $timestamps = false;
	protected $table = 'terceros_direcciones';
	public static function saveData($data){
		$data['fecha_inserta'] = Carbon::now();
		return TercerosDirecciones::create($data);
	}
}
