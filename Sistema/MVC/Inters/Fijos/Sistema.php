<?php

namespace Gof\Sistema\MVC\Inters\Fijos;

use Gof\Sistema\MVC\Aplicacion\DAP\DAP;
use Gof\Sistema\MVC\Interfaz\Ejecutable;
use Gof\Sistema\MVC\Sistema as SistemaMVC;

/**
 * Inter que agrega una referencia al sistema en el propio DAP
 *
 * @package Gof\Sistema\MVC\Inters\Fijos
 */
class Sistema implements Ejecutable
{
    /**
     * Nombre del método asociado
     *
     * @var string
     */
    public const METODO = 'sistema';

    /**
     * Instancia del sistema MVC
     *
     * @var SistemaMVC
     */
    private SistemaMVC $sistema;

    /**
     * Constructor
     *
     * @param SistemaMVC $sistema Instancia del sistema.
     */
    public function __construct(SistemaMVC $sistema)
    {
        $this->sistema = $sistema;
    }

    /**
     * Ejecuta el inter
     *
     * Agrega al gestor de dependencias una referencia al sistema MVC.
     *
     * @param DAP $gdi Dap de nivel 2
     */
    public function ejecutar(DAP $gdi)
    {
        $gdi->definir(SistemaMVC::class, $this->sistema);
        $gdi->asociarMetodo(self::METODO, SistemaMVC::class);
    }

}
