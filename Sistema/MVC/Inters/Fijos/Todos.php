<?php

namespace Gof\Sistema\MVC\Inters\Fijos;

use Gof\Sistema\MVC\Inters\Lista;

/**
 * Lista todos los inters que serán cargados obligatoriamente
 *
 * @package Gof\Sistema\MVC\Inters\Fijos
 */
class Todos implements Lista
{

    /**
     * Lista de todos los inters fijos
     *
     * @return Gof\Sistema\MVC\Interfaz\Ejecutable[]
     */
    public function lista(): array
    {
        return [
            new ConfiguracionDelGDI(),
            new Session(),
            new Cookies(),
            new ACSRF(),
            new Redireccion(),
        ];
    }

}
