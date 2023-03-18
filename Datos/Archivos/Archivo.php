<?php

namespace Gof\Datos\Archivos;

use Exception;
use Gof\Interfaz\Archivos\Archivo as IArchivo;

/**
 * Tipo de datos para archivos
 *
 * Tipo de datos para archivos alojados localmente en el servidor que lo ejecuta.
 *
 * Esta clase sirve para garantizar que el archivo que contiene existe y es legible.
 *
 * @package Gof\Datos\Archivos
 */
class Archivo implements IArchivo
{
    /**
     * @var string Ruta donde está ubicado el archivo
     */
    private string $ruta;

    /**
     * Constructor
     *
     * @param string $rutaDelArchivo Ruta donde se encuentra el archivo
     *
     * @throws Exception Si no existe el archivo
     * @throws Exception Si no es legible
     */
    public function __construct(string $rutaDelArchivo)
    {
        $this->ruta = $rutaDelArchivo;

        // TAREA
        //  Cambiar las excepciones genéricas por alguna más relativa

        if( file_exists($rutaDelArchivo) === false ) {
            throw new Exception("No existe ningún archivo en '{$rutaDelArchivo}'");
        }

        if( is_readable($rutaDelArchivo) === false ) {
            throw new Exception("No se puede leer el archivo '{$rutaDelArchivo}'");
        }

        if( is_file($rutaDelArchivo) === false ) {
            throw new Exception("La ruta indicada no apunta a un archivo. Ruta: {$rutaDelArchivo}");
        }
    }

    /**
     * Devuelve la ruta del archivo
     *
     * @return string Ubicación del archivo.
     */
    public function ruta(): string
    {
        return $this->ruta;
    }

}
