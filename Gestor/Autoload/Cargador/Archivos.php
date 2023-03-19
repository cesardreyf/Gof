<?php

namespace Gof\Gestor\Autoload\Cargador;

use Gof\Datos\Bits\Mascara\MascaraDeBits;
use Gof\Gestor\Autoload\Excepcion\ArchivoInaccesible;
use Gof\Gestor\Autoload\Excepcion\ArchivoInexistente;
use Gof\Gestor\Autoload\Interfaz\Cargador;
use Gof\Interfaz\Bits\Mascara;

/**
 * Cargador de archivos locales
 *
 * Gestiona las cargas de archivos locales para el gestor de autoload
 *
 * @package Gof\Gestor\Autoload\Cargador
 */
class Archivos implements Cargador
{
    /**
     *  @var int Indicador para lanzar excepciones en caso de errores
     */
    const LANZAR_EXCEPCIONES = 4;

    /**
     *  @var int Indicador para incluir automáticamente la extensión *.php* a las rutas
     */
    const INCLUIR_EXTENSION = 8;

    /**
     *  @var int Máscara de bits con la configuración por defecto
     */
    const CONFIGURACION_POR_DEFECTO = self::LANZAR_EXCEPCIONES;

    /**
     *  @var int Error: el archivo no existe
     */
    const ERROR_ARCHIVO_INEXISTENTE = 1;

    /**
     *  @var int Error: el archivo es ilegible
     */
    const ERROR_ARCHIVO_ILEGIBLE = 2;

    /**
     *  @var int Último error ocurrido
     */
    private int $error = 0;

    /**
     *  @var Mascara Máscara de bits con la configuración interna
     */
    private Mascara $configuracion;

    /**
     * Constructor
     *
     * @param int $configuracion Máscara de bits con la configuración deseada
     */
    public function __construct(int $configuracion = self::CONFIGURACION_POR_DEFECTO)
    {
        $this->configuracion = new MascaraDeBits($configuracion);
    }

    /**
     * Carga el archivo
     *
     * Sí Archivos::INCLUIR_EXTENSION está activo se agregará la cadena *.php* a la
     * ruta del archivo.
     *
     * @param string $rutaDelArchivo Ruta del archivo a ser cargado
     *
     * @return bool Devuelve **true** en caso de éxito o **false** de lo contrario
     *
     * @see Archivos::error() para ver el error ocurrido si falla la carga
     *
     * @throws ArchivoInexistente si la ruta no apunta a ningún archivo
     * @throws ArchivoInaccesible si el archivo no puede ser leído
     */
    public function cargar(string $rutaDelArchivo): bool
    {
        if( $this->configuracion->activados(self::INCLUIR_EXTENSION) ) {
            $rutaDelArchivo .= '.php';
        }

        if( file_exists($rutaDelArchivo) === false ) {
            if( $this->configuracion->activados(self::LANZAR_EXCEPCIONES) ) {
                throw new ArchivoInexistente($rutaDelArchivo);
            }

            $this->error = self::ERROR_ARCHIVO_INEXISTENTE;
            return false;
        }

        if( is_readable($rutaDelArchivo) === false ) {
            if( $this->configuracion->activados(self::LANZAR_EXCEPCIONES) ) {
                throw new ArchivoInaccesible($rutaDelArchivo);
            }

            $this->error = self::ERROR_ARCHIVO_ILEGIBLE;
            return false;
        }

        require $rutaDelArchivo;
        return true;
    }

    /**
     * Obtiene la configuracion interna
     *
     * @return Mascara Devuelve una tipo de datos Mascara de bits
     */
    public function configuracion(): Mascara
    {
        return $this->configuracion;
    }

    /**
     * Obtiene el identificador del último error ocurrido
     *
     * @return int Devuelve el último error
     */
    public function error(): int
    {
        return $this->error;
    }

    /**
     * Limpia los errores
     */
    public function limpiarErrores()
    {
        $this->error = 0;
    }

}
