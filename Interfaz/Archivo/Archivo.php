<?php

namespace Gof\Interfaz\Archivo;

interface Archivo
{
    /**
     *  Ruta completa del archivo
     *
     *  @return string Devuelve la ruta al archivo
     */
    public function ruta(): string;
}
