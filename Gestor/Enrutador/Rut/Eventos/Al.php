<?php

namespace Gof\Gestor\Enrutador\Rut\Eventos;

/**
 * Enumera los eventos posibles
 *
 * @package Gof\Gestor\Enrutador\Rut\Eventos
 */
enum Al
{
    /**
     * Tipo de evento producido al agregar una nueva ruta hija.
     */
    case Agregar;

    /**
     * Tipo de evento producido cuando el enrutador procesa la solicitud y haya la ruta final
     */
    case Procesar;
}
