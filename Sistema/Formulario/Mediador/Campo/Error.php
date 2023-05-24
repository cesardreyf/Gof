<?php

namespace Gof\Sistema\Formulario\Mediador\Campo;

use Gof\Interfaz\Formulario\Campo;

abstract class Error
{

    static public function reportar(Campo $campo, string $mensaje, int $codigo)
    {
        $campo->error()->codigo($codigo);
        $campo->error()->mensaje($mensaje);
    }

}
