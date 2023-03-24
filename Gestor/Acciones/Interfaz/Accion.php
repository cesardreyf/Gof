<?php

namespace Gof\Gestor\Acciones\Interfaz;

interface Accion
{
    /**
     * Recibe una propiedad y un identificador
     *
     * @param mixed $elemento Elemento recibido.
     * @param string $identificador Clave asociada al elemento.
     */
    public function accionar(mixed $elemento, string $identificador);
}
