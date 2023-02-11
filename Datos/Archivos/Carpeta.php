<?php

namespace Gof\Datos\Archivos;

use Exception;
use Gof\Interfaz\Archivos\Carpeta as ICarpeta;

class Carpeta implements ICarpeta
{
    private $ruta;

    /**
     *  Crea una instancia de la clase Carpeta
     *
     *  Esta clase sirve para garantizar que la carpeta que contiene existe y es legible.
     *
     *  @param string $rutaDeLaCarpeta Ruta donde se encuentra la carpeta
     *
     *  @throws Exception Si no existe la carpeta
     *  @throws Exception Si no es legible
     */
    public function __construct(string $rutaDeLaCarpeta)
    {
        $this->ruta = rtrim($rutaDeLaCarpeta, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        if( file_exists($rutaDeLaCarpeta) === false ) {
            throw new Exception("No existe ninguna carpeta en '{$rutaDeLaCarpeta}'");
        }

        if( is_readable($rutaDeLaCarpeta) === false ) {
            throw new Exception("No se puede leer el archivo '{$rutaDeLaCarpeta}'");
        }

        if( is_dir($rutaDeLaCarpeta) === false ) {
            throw new Exception("La ruta indicada no apunta a una carpeta. Ruta: {$rutaDeLaCarpeta}");
        }
    }

    /**
     *  Devuelve la ruta al archivo
     *
     *  @return string Retorna la ruta completa del archivo
     */
    public function ruta(): string
    {
        return $this->ruta;
    }

}
