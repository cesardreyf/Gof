<?php

namespace Gof\Sistema\MVC\Rut\Datos;

use Gof\Gestor\Enrutador\Rut\Eventos\Ruta as IRuta;
use Gof\Interfaz\Id;

/**
 * Ruta adaptada al sistema MVC
 *
 * Adapta la ruta de Rut para agregar rasgos importantes del sistema MVC.
 *
 * @package Gof\Sistema\MVC\Rut\Datos
 */
class Ruta extends IRuta implements Id
{
    use Extension\Id;
    use Extension\Inters;
}
