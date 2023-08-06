<?php

namespace Gof\Sistema\MVC\Inters\Fijos;

use Gof\Gestor\Redireccion\Redirigir;
use Gof\Sistema\MVC\Aplicacion\DAP\DAP;
use Gof\Sistema\MVC\Interfaz\Ejecutable;

/**
 * Inter para las redirecciones
 *
 * @package Gof\Sistema\MVC\Inters\Fijos
 */
class Redireccion implements Ejecutable
{
    /**
     * Nombre del método asociado
     *
     * @var string
     */
    public const METODO = 'redirigir';

    /**
     * Ejecuta el inter
     *
     * Agrega al gestor de dependencias el gestor de redirección
     *
     * @param DAP $gdi Dap de nivel 2
     */
    public function ejecutar(DAP $gdi)
    {
        $gdi->agregar(Redirigir::class, function() {
            return new Redirigir();
        });

        $gdi->asociarMetodo(self::METODO, Redirigir::class);
    }

}
