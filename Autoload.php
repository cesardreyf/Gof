<?php

namespace Gof;

/**
 * Autoload de gof
 *
 * Esta clase solo sirve para cargar automáticamente las clases de Gof. Solo sirve en caso
 * de no usar composer.
 *
 * @package Gof
 */
class Autoload
{
    /**
     *  @var string Espacio de nombre raíz reservado para el mini framework este...
     */
    const ESPACIO_DE_NOMBRE = 'Gof';

    /**
     *  Crea una instancia del Autoload para Gof
     *
     *  @param string|null $carpetaBase     Ruta de la carpeta raíz o NULL para automatizarlo
     *  @param string|null $espacioDeNombre Espacio de nombre de Gof o NULL para automatizarlo
     */
    public function __construct(string $carpetaBase = __DIR__, string $espacioDeNombre = self::ESPACIO_DE_NOMBRE)
    {
        spl_autoload_register(function($nombreDeLaClase) use ($carpetaBase, $espacioDeNombre) {
            self::cargar($nombreDeLaClase, $carpetaBase, $espacioDeNombre);
        });
    }

    /**
     *  Carga un archivo segun el nombre de la clase y una carpeta raíz
     *
     *  Carga un archivo en función del nombre de la clase, su espacio de nombre y una carpeta raíz.
     *
     *  @param string $nombreDeLaClase Nombre de la clase/archivo a ser cargado
     *  @param string $carpetaBase     Ruta de la carpeta donde se comenzará a buscar el archivo
     *  @param string $espacioDeNombre Espacio de nombre padre/raíz
     *
     *  @return bool Devuelve **true** en caso de cargar exitosamente el archivo, **false** de lo contrario
     */
    public static function cargar(string $nombreDeLaClase, string $carpetaBase, string $espacioDeNombreReservado): bool
    {
        $primerEspacioDeNombre = strpos($nombreDeLaClase, '\\');
        $espacioDeNombreRequerido = substr($nombreDeLaClase, 0, $primerEspacioDeNombre);

        if( $espacioDeNombreRequerido !== $espacioDeNombreReservado ) {
            return false;
        }

        $rutaLimpiaDeLaCarpeta = rtrim($carpetaBase, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $rutaDelArchivoDeLaClaseInstanciable = trim(str_replace('\\', DIRECTORY_SEPARATOR, substr($nombreDeLaClase, $primerEspacioDeNombre)), DIRECTORY_SEPARATOR);

        require($rutaLimpiaDeLaCarpeta . $rutaDelArchivoDeLaClaseInstanciable . '.php');
        return true;
    }

}
