<?php

namespace App\Models;

use App\Casts\RolesCast;
use App\Models\Compra;
use App\Models\Imagen;
use App\Models\Producto;
use App\Models\Enums\Rol;
use Database\Factories\CategoriasFactory;
use Database\Factories\UsuariosFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = 'usuarios';

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return UsuariosFactory::new();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'roles' => RolesCast::class,
    ];

    # Acccessors

    public function getIsEncargadoAttribute()
    {
        return $this->roles->contains(function ($rol) {
            return $rol == Rol::ENCARGADO;
        });
    }
    public function getIsClienteAttribute()
    {
        return $this->roles->contains(function ($rol) {
            return $rol == Rol::CLIENTE;
        });
    }
    public function getIsSupervisorAttribute()
    {
        return $this->roles->contains(function ($rol) {
            return $rol == Rol::SUPERVISOR;
        });
    }
    public function getIsContadorAttribute()
    {
        return $this->roles->contains(function ($rol) {
            return $rol == Rol::CONTADOR;
        });
    }

    # Relationships

    public function productos()
    {
      return $this->hasMany(Producto::class, 'usuario_id');
    }

    public function ventas()
    {
      return $this->hasManyThrough(Compra::class, Producto::class);
    }

    public function compras()
    {
      return $this->hasMany(Compra::class, 'usuario_id');
    }

    public function imagen()
    {
        return $this->morphOne(Imagen::class, 'imagenes');
    }

    public function cometarios()
    {
        return $this->morphMany(Comentario::class, 'comentarios');
    }
}
