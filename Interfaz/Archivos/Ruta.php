<?php

namespace Gof\Interfaz\Archivos;

interface Ruta
{
    /**
     *  Ruta completa del archivo
     *
     *  @return string Devuelve la ruta al archivo
     */
    public function ruta(): string;
}
