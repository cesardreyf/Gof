<?php

namespace Gof\Sistema\Formulario\Datos\Campo\TipoTabla;

use Gof\Datos\Errores\Mensajes\ErrorAsociativoMultiple as GestorDeError;
use Gof\Sistema\Formulario\Interfaz\Campo\Error as IError;

class ErrorAsociativo extends GestorDeError implements IError
{

    public function obtener(): mixed
    {
        return $this->lista();
    }

}
