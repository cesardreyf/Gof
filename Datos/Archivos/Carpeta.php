<?php

namespace Gof\Datos\Archivos;

use Exception;
use Gof\Interfaz\Archivos\Carpeta as ICarpeta;

/**
 * Tipo de datos para carpetas
 *
 * Tipo de datos para carpetas alojados localmente en el mismo servidor donde se ejecuta.
 *
 * Esta clase sirve para garantizar que la carpeta a la que apunta existe y es legible.
 *
 * @package Gof\Datos\Archivos
 */
class Carpeta implements ICarpeta
{
    /**
     * @var string Ubicación de la carpeta
     */
    private string $ruta;

    /**
     * Constructor
     *
     * @param string $rutaDeLaCarpeta Ruta donde se encuentra la carpeta
     *
     * @throws Exception Si no existe la carpeta
     * @throws Exception Si no es legible
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
     * Devuelve la ruta de la carpeta
     *
     * @return string Retorna la ubicación de la carpeta
     */
    public function ruta(): string
    {
        return $this->ruta;
    }

}
