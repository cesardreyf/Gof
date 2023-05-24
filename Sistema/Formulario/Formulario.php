<?php

namespace Gof\Sistema\Formulario;

use Gof\Datos\Formulario\Campo;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Interfaz\Tipos;
use Gof\Sistema\Formulario\Validar\ValidarExistencia;
use Gof\Sistema\Formulario\Validar\ValidarTipos;

/**
 * Sistema para obtener datos de formulario
 *
 * Clase que gestiona la obtención de datos desde formularios web.
 *
 * @package Gof\Sistema\Formulario
 */
class Formulario implements Tipos, Errores
{
    /**
     * @var array Datos del formulario
     */
    private array $datos;

    /**
     * @var array Lista de campos del formulario
     */
    private array $campos;

    /**
     * Constructor
     *
     * @param array Array con los datos desde donde se obtendrán los datos del formulario
     */
    public function __construct(array $datos)
    {
        $this->campos = [];
        $this->datos = $datos;
    }

    /**
     * Obtiene un elemento de tipo Campo con los datos del formulario
     *
     * Crea un elemento de tipo Campo con el valor asociado a la **clave**. Si
     * ocurren errores estos son almacenados internamente dentro del objeto
     * devuelto.
     *
     * @param string $clave Nombre del campo a obtener desde el formulario.
     * @param int    $tipo  Tipo de dato a obtener (ver Tipos).
     *
     * @return Campo Devuelve un objeto de tipo Campo.
     *
     * @see Tipos
     */
    public function campo(string $clave, int $tipo = self::TIPO_STRING): Campo
    {
        if( isset($this->campos[$clave]) ) {
            return $this->campos[$clave];
        }

        $campo = new Campo($clave, $tipo);
        $siElCampo = new ValidarExistencia($campo, $this->datos);

        if( $siElCampo->existe() ) {
            $campo->valor = $this->datos[$clave];

            new ValidarTipos($campo);
        }

        return $this->campos[$clave] = $campo;
    }

    /**
     * Obtiene una lista con todos los errores
     *
     * Genera un array con todos los errores ocurridos en los campos
     * almacenados internamente. Estos son asociados a los nombres de sus
     * propios campos.
     *
     * @return array Devuelve un array con todos los errores
     */
    public function errores(): array
    {
        return array_map(function($campo) {
            return $campo->error()->mensaje();
        }, array_filter($this->campos, function($campo) {
            return $campo->error()->hay();
        }));
    }

}
