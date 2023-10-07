<?php

namespace Gof\Sistema\MVC\Inters\Fijos;

use Gof\Gestor\Solicitud\GestorDeSolicitud;
use Gof\Sistema\MVC\Aplicacion\DAP\DAP;
use Gof\Sistema\MVC\Interfaz\Ejecutable;

/**
 * Inter que agrega el gestor de solicitud
 *
 * @package Gof\Sistema\MVC\Inters\Fijos
 */
class Solicitud implements Ejecutable
{

    /**
     * Nombre del método asociado
     *
     * @var string
     */
    public const METODO = 'solicitud';

    /**
     * Ejecuta el inter
     *
     * Agrega al gestor de dependencias un gestor de solicitudes HTTP
     *
     * @param DAP $gdi Dap de nivel 2
     */
    public function ejecutar(DAP $gdi)
    {
        $gdi->agregar(GestorDeSolicitud::class, function() {
            return new GestorDeSolicitud(
                $_GET ?? [],
                $_POST ?? [],
                $_SERVER ?? [],
            );
        });

        $gdi->asociarMetodo(self::METODO, GestorDeSolicitud::class);
    }

}
