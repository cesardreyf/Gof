<?php

namespace Gof\Sistema\MVC\Inters\Fijos;

use Gof\Gestor\Dependencias\Simple\Dependencias;
use Gof\Sistema\MVC\Aplicacion\DAP\DAP;
use Gof\Sistema\MVC\Interfaz\Ejecutable;

/**
 * Inter que configura el Gestor de Dependencias Interno
 *
 * @package Gof\Sistema\MVC\Inters\Fijos
 */
class ConfiguracionDelGDI implements Ejecutable
{

    /**
     * Ejecuta el inter
     *
     * Configura el gestor de dependencias.
     *
     * @param DAP $gdi Dap de nivel 2
     */
    public function ejecutar(DAP $gdi)
    {
        $gdi->configuracion()->activar(
            Dependencias::PERMITIR_CAMBIAR,
            Dependencias::PERMITIR_REMOVER,
            Dependencias::PERMITIR_DEFINIR,
            Dependencias::LANZAR_EXCEPCION,
        );
    }

}
