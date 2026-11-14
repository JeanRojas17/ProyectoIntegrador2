<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROL_ADMINISTRADOR = 'administrador';
    public const ROL_CONDUCTOR = 'conductor';
    public const ROL_CLIENTE = 'cliente';
    public const ROL_AUXILIAR = 'auxiliar';
    public const ROL_CONSULTA = 'consulta';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function cliente(): HasOne
    {
        return $this->hasOne(Cliente::class);
    }

    public function conductor(): HasOne
    {
        return $this->hasOne(Conductor::class);
    }

    public function esAdministrador(): bool
    {
        return $this->rol === self::ROL_ADMINISTRADOR;
    }

    public function esConductor(): bool
    {
        return $this->rol === self::ROL_CONDUCTOR;
    }

    public function esCliente(): bool
    {
        return $this->rol === self::ROL_CLIENTE;
    }

    public function esAuxiliar(): bool
    {
        return $this->rol === self::ROL_AUXILIAR;
    }

    public function esConsulta(): bool
    {
        return $this->rol === self::ROL_CONSULTA;
    }

    public function puedeGestionar(): bool
    {
        return $this->esAdministrador();
    }

    public function esSoloLectura(): bool
    {
        return $this->esConsulta();
    }
}
