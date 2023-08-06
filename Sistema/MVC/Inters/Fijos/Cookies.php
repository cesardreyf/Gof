<?php

namespace Gof\Sistema\MVC\Inters\Fijos;

use Gof\Contrato\Cookies\Cookies as ICookies;
use Gof\Gestor\Cookies\Cookies as GestorDeCookies;
use Gof\Sistema\MVC\Aplicacion\DAP\DAP;
use Gof\Sistema\MVC\Interfaz\Ejecutable;

/**
 * Inter para el gestor de cookies
 *
 * @package Gof\Sistema\MVC\Inters\Fijos
 */
class Cookies implements Ejecutable
{
    /**
     * Nombre del método asociado
     *
     * @var string
     */
    public const METODO = 'cookies';

    /**
     * Ejecuta el inter
     *
     * Agrega al gestor de dependencias el gestor de cookies
     *
     * @param DAP $gdi Dap de nivel 2
     */
    public function ejecutar(DAP $gdi)
    {
        $gdi->agregar(ICookies::class, function() {
            return new GestorDeCookies();
        });

        $gdi->asociarMetodo(self::METODO, ICookies::class);
    }

}
