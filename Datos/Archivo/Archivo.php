<?php

namespace Gof\Datos\Archivo;

use Exception;
use Gof\Interfaz\Archivo\Archivo as IArchivo;

class Archivo implements IArchivo
{
    private $ruta;

    /**
     *  Crea una instancia de la clase Archivo
     *
     *  Esta clase sirve para garantizar que el archivo que contiene existe y es legible.
     *
     *  @param string $rutaDelArchivo Ruta donde se encuentra el archivo
     *
     *  @throws Exception Si no existe el archivo
     *  @throws Exception Si no es legible
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
     *  Devuelve la ruta al archivo
     *
     *  @return string Retorna la ruta completa del archivo
     */
    public function ruta(): string
    {
        return $this->ruta;
    }

}
