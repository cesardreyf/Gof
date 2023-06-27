<?php

namespace Gof\Sistema\Formulario\Datos\Campo;

use Gof\Datos\Errores\Mensajes\Error as GestorDeError;
use Gof\Sistema\Formulario\Interfaz\Campo\Error as ErrorInterfaz;

/**
 * Gestor de error por defecto
 *
 * Gestiona el almacenamiento de los errores de los campos.
 *
 * @package Gof\Sistema\Formulario\Datos\Campo
 */
class Error extends GestorDeError implements ErrorInterfaz
{

    /**
     * Obtiene el último mensaje de error agregado
     *
     * Por defecto retorna el último mensaje agregado.
     * Internamente, esta función llama al método **mensaje**.
     *
     * @return string|array
     */
    public function obtener(): mixed
    {
        return $this->mensaje();
    }

}
