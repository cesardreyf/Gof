<?php

namespace Gof\Gestor\Solicitud\Excepcion;

class MetodoHttpInexistente extends Excepcion
{

    protected $message = 'No existe ningún método HTTP en la variable _SERVER';

}
