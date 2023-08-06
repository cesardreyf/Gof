<?php

namespace Gof\Sistema\MVC\Inters\Fijos;

use Gof\Contrato\Session\Session as ISession;
use Gof\Gestor\Session\Session as GestorDeSession;
use Gof\Sistema\MVC\Aplicacion\DAP\DAP;
use Gof\Sistema\MVC\Interfaz\Ejecutable;

/**
 * Inter para el gestor de session
 *
 * @package Gof\Sistema\MVC\Inters\Fijos
 */
class Session implements Ejecutable
{
    /**
     * Nombre del método asociado
     *
     * @var string
     */
    public const METODO = 'session';

    /**
     * Ejecuta el inter
     *
     * Agrega al gestor de dependencias el gestor de session
     *
     * @param DAP $gdi Dap de nivel 2
     */
    public function ejecutar(DAP $gdi)
    {
        $gdi->agregar(ISession::class, function() {
            return new GestorDeSession();
        });

        $gdi->asociarMetodo(self::METODO, ISession::class);
    }

}
