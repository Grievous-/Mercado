<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\ComprasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Compra extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'cardNumber',
        'calificacion',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return ComprasFactory::new();
    }

    protected $table = 'compras';

    # Relationships

    public function comprador()
    {
      return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function producto()
    {
      return $this->belongsTo(Produto::class, 'producto_id');
    }

    public function comentarios()
    {
        return $this->morphMany(Imagen::class, 'comentarios');
    }

    // public function comentarios()
    // {
    //   return $this->hasMany(Comentario::class, 'compra_id');
    // }

    public function imagen()
    {
        return $this->morphOne(Imagen::class, 'imagenes');
    }
}
