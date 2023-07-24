<?php

namespace Gof\Sistema\MVC\Aplicacion\DAP\Solicitud;

enum Metodo: string
{
    case Get = 'GET';
    case Put = 'PUT';
    case Post = 'POST';
    case Delete = 'DELETE';
}
