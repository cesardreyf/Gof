<?php

namespace Gof\Sistema\MVC\Inters\Fijos;

use Gof\Sistema\MVC\Interfaz\Ejecutable;
use Gof\Gestor\Dependencias\Simple\Dependencias;

class ConfiguracionDelGDI implements Ejecutable
{

    public function ejecutar($gdi)
    {
        $gdi->configuracion()->activar(
            Dependencias::PERMITIR_CAMBIAR,
            Dependencias::PERMITIR_REMOVER,
            Dependencias::PERMITIR_DEFINIR,
            Dependencias::LANZAR_EXCEPCION,
        );
    }

}
