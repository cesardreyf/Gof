<?php

namespace Gof\Gestor\Autoload\Cargador;

use Gof\Gestor\Autoload\Excepcion\ArchivoInaccesible;
use Gof\Gestor\Autoload\Excepcion\ArchivoInexistente;
use Gof\Gestor\Autoload\Interfaz\Cargador;

class Archivos implements Cargador
{
    // const VALIDAR_EXISTENCIA = 1;
    // CONST VALIDAR_LECTURA = 2;

    /**
     *  @var int Indica que se lanzará una excepción cuando ocurra un error
     */
    const LANZAR_EXCEPCIONES = 4;

    /**
     *  @var int Indica que se agregará automaticamente la extensión '.php' a las rutas
     */
    const INCLUIR_EXTENSION = 8;

    /**
     *  @var int Máscara de bits con la configuración por defecto
     */
    const CONFIGURACION_POR_DEFECTO = self::LANZAR_EXCEPCIONES;

    /**
     *  @var int Indica que no existe el archivo
     */
    const ERROR_ARCHIVO_INEXISTENTE = 1;

    /**
     *  @var int Indica que el archivo no es legible
     */
    const ERROR_ARCHIVO_ILEGIBLE = 2;

    /**
     *  @var int Último error ocurrido
     */
    private $error = 0;

    /**
     *  @var int Máscara de bits con la configuración interna
     */
    private $config;

    /**
     *  Crea una instancia del cargador de archivos del gestor de Autoloads
     *
     *  @param int $configuracion Máscara de bits con la configuración deseada
     */
    public function __construct(int $configuracion = self::CONFIGURACION_POR_DEFECTO)
    {
        $this->config = $configuracion;
    }

    /**
     *  Simplemente carga el archivo
     *
     *  Si INCLUIR_EXTENSION está activo se agregará la cadena '.php' a la ruta recibida.
     *
     *  @param string $rutaDelArchivo Ruta del archivo a ser cargado
     *
     *  @throws ArchivoInexistente si la ruta no apunta a ningún archivo
     *  @throws ArchivoInaccesible si el archivo no puede ser leído
     *
     *  @see Archivos::error() para ver el error ocurrido si falla la carga
     *
     *  @return bool Devuelve **true** en caso de éxito o **false** de lo contrario
     */
    public function cargar(string $rutaDelArchivo): bool
    {
        if( $this->config & self::INCLUIR_EXTENSION ) {
            $rutaDelArchivo .= '.php';
        }

        if( file_exists($rutaDelArchivo) === false ) {
            if( $this->config & self::LANZAR_EXCEPCIONES ) {
                throw new ArchivoInexistente($rutaDelArchivo);
            }

            $this->error = self::ERROR_ARCHIVO_INEXISTENTE;
            return false;
        }

        if( is_readable($rutaDelArchivo) === false ) {
            if( $this->config & self::LANZAR_EXCEPCIONES ) {
                throw new ArchivoInaccesible($rutaDelArchivo);
            }

            $this->error = self::ERROR_ARCHIVO_ILEGIBLE;
            return false;
        }

        require $rutaDelArchivo;
        return true;
    }

    /**
     *  Obtiene y/o define la configuración interna
     *
     *  @param int|null $configuracion Máscara de bits con la nueva configuración o **null** para obtener el actual
     *
     *  @return int Devuelve la máscara de bits con la configuración actual
     */
    public function configuracion(?int $configuracion = null): int
    {
        return $configuracion === null ? $this->config : $this->config = $configuracion;
    }

    /**
     *  Obtiene el identificador del último error ocurrido
     *
     *  @return int Devuelve el último error
     */
    public function error(): int
    {
        return $this->error;
    }

    /**
     *  Limpia los errores
     */
    public function limpiarErrores()
    {
        $this->error = 0;
    }

}
