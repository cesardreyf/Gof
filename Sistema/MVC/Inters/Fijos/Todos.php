<?php

namespace Gof\Sistema\MVC\Inters\Fijos;

use Gof\Sistema\MVC\Inters\Lista;
use Gof\Sistema\MVC\Sistema as SistemaMVC;

/**
 * Lista todos los inters que serán cargados obligatoriamente
 *
 * @package Gof\Sistema\MVC\Inters\Fijos
 */
class Todos implements Lista
{

    /**
     * Constructor
     *
     * @param SistemaMVC $sistema Instancia del sistema
     */
    public function __construct(private SistemaMVC $sistema)
    {
    }

    /**
     * Lista de todos los inters fijos
     *
     * @return Gof\Sistema\MVC\Interfaz\Ejecutable[]
     */
    public function lista(): array
    {
        return [
            new ConfiguracionDelGDI(),
            new Sistema($this->sistema),
            new Session(),
            new Cookies(),
            new ACSRF(),
            new Redireccion(),
        ];
    }

}
