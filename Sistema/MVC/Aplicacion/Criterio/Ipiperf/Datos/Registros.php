<?php

namespace Gof\Sistema\MVC\Aplicacion\Criterio\Ipiperf\Datos;

/**
 * Registros de datos
 *
 * Almacena datos que alteran el comportamiento del controlador.
 *
 * @package Gof\Sistema\MVC\Aplicacion\Criterio\Ipiperf\Datos
 */
class Registros
{
    /**
     * Determina que hubo un error
     *
     * @var bool
     */
    public bool $error = false;

    /**
     * Determina que en caso de error continúe con el flujo del programa
     *
     * Si hay un error y este estado es **true** se llamará al método error
     * y luego continuará ejecutando las siguientes funciones.
     *
     * @var bool
     */
    public bool $continuar = false;

    /**
     * Determina si saltar las funciones básicas
     *
     * Si este estado es establecido a **true** el criterio saltará las
     * funciones indice y/o posindice.
     *
     * @var bool
     */
    public bool $saltar = false;

    /**
     * Determina que se debe renderizar la vista
     *
     * Si el estado es **true** se llamará al método renderizar antes de
     * finalizar el controlador.
     *
     * @var bool
     */
    public bool $renderizar = true;
}
