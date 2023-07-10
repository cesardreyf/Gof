<?php

namespace Gof\Sistema\MVC\Rutas;

use Gof\Contrato\Enrutador\Enrutador;

/**
 * Configuración del gestor de rutas del sistema MVC
 *
 * @package Gof\Sistema\MVC\Rutas
 */
class Configuracion
{
    /**
     * Clave empleada para obtener la solicitud desde _GET
     *
     * @var string
     */
    public string $urlClave;

    /**
     * Caracter usado para separar la solicitud en recursos
     *
     * @var string
     */
    public string $separador;

    /**
     * Instancia del enrutador a usarse para procesar la solicitud
     *
     * @var Enrutador
     */
    public Enrutador $enrutador;
}
