<?php

namespace Gof\Datos\Errores;

/**
 * Componente de datos para conjuntos de par claves, valor asociados a errores
 *
 * @package Gof\Datos\Errores
 */
class ErrorAsociativo extends ErrorAbstracto
{

    /**
     * Agrega un error y lo asocia a un identificador (clave)
     *
     * Si ya existe un error previo asociado con la misma clave esta reemplazará el error.
     *
     * @param string $clave Clave asociada al error
     * @param int    $error Identificador del error
     *
     * @return int Devuelve el mismo valor recibido por parámetro
     */
    public function agregar(int $error, string $clave): int
    {
        return $this->errores[$clave] = $error;
    }

}
