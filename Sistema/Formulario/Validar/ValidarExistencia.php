<?php

namespace Gof\Sistema\Formulario\Validar;

use Gof\Interfaz\Formulario\Campo;
use Gof\Sistema\Formulario\Interfaz\Errores;

class ValidarExistencia
{
    public const ERROR_MENSAJE = 'Campo vacío';

    private bool $existe = true;

    public function __construct(Campo $campo, array $datos)
    {
        if( isset($datos[$campo->clave()]) === false ) {
            $campo->error()->codigo(Errores::ERROR_CAMPO_INEXISTENTE);
            $campo->error()->mensaje(self::ERROR_MENSAJE);
            $this->existe = false;
        }
    }

    public function existe(): bool
    {
        return $this->existe;
    }

}
