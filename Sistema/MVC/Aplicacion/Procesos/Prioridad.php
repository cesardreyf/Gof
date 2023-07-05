<?php

namespace Gof\Sistema\MVC\Aplicacion\Procesos;

/**
 * Lista de prioridades del gestor de procesos
 *
 * Enumera las prioridades del que hace uso el gestor de procesos.
 *
 * @package Gof\Sistema\MVC\Aplicacion\Procesos
 */
enum Prioridad: int
{
    case Alta = 0;

    case Media = 1;

    case Baja = 2;
}
