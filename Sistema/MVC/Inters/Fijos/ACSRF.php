<?php

namespace Gof\Sistema\MVC\Inters\Fijos;

use Gof\Contrato\Cookies\Cookies;
use Gof\Contrato\Session\Session;
use Gof\Sistema\ACSRF\ACSRF as SistemaASCRF;
use Gof\Sistema\MVC\Aplicacion\DAP\DAP;
use Gof\Sistema\MVC\Interfaz\Ejecutable;

/**
 * Inter para el sistema anti csrf
 *
 * @package Gof\Sistema\MVC\Inters\Fijos
 */
class ACSRF implements Ejecutable
{
    /**
     * Nombre del método asociado
     *
     * @var string
     */
    public const METODO = 'acsrf';

    /**
     * Ejecuta el inter
     *
     * Agrega al gestor de dependencias el sistema anti csrf
     *
     * @param DAP $gdi Dap de nivel 2
     */
    public function ejecutar(DAP $gdi)
    {
        $gdi->agregar(SistemaASCRF::class, function() use ($gdi) {
            return new SistemaASCRF($gdi->obtener(Session::class), $gdi->obtener(Cookies::class));
        });

        $gdi->asociarMetodo(self::METODO, SistemaASCRF::class);
    }

}
