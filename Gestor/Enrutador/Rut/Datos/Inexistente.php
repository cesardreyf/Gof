<?php

namespace Gof\Gestor\Enrutador\Rut\Datos;

use Gof\Gestor\Enrutador\Rut\Interfaz\Inexistente as IInexistente;

/**
 * Ruta con datos para el gestor de rutas
 *
 * @package Gof\Gestor\Enrutador\Rut\Datos
 */
class Inexistente implements IInexistente
{
    /**
     * Almacena el nombre completo de la clase
     *
     * @var string
     */
    private string $clase = '';

    /**
     * Obtiene y/o define la clase del controlador para rutas inexistentes
     *
     * Si una ruta hijo no coincide con lo solicitado Rut usará esta clase
     * para el controlador a ejecutarse.
     *
     * Este dato es heredable por las rutas hijos.
     *
     * @param ?string $clase Nombre completo de la clase o **null** para obtener el actual.
     *
     * @return string
     */
    public function clase(?string $clase = null): string
    {
        return $this->clase = $clase ?? $this->clase;
    }

}
