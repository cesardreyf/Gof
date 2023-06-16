<?php

namespace Gof\Sistema\Formulario\Gestor;

use Gof\Sistema\Formulario\Contratos\Errores as ErroresInterfaz;
use Gof\Sistema\Formulario\Interfaz\Campo;

/**
 * Gestor de errores
 *
 * Gestor encargado de almacenar los errores del sistema de formulario.
 *
 * @package Gof\Sistema\Formulario\Gestor
 */
class Errores implements ErroresInterfaz
{
    /**
     * @var array Caché de errores
     */
    private array $errores;

    /**
     * @var bool Indica si actualizar la caché de errores
     */
    private bool $actualizarCache;

    /**
     * @var array Referencia a la lista de campos del sistema
     */
    private array $campos;

    /**
     * Constructor
     *
     * @param array &$listaDeCampos Referencia a la lista de campos del sistema.
     */
    public function __construct(array &$listaDeCampos)
    {
        $this->errores = [];
        $this->actualizarCache = true;
        $this->campos =& $listaDeCampos;
    }

    /**
     * Indica si hay errores
     *
     * Devuelve **true** solo si hay errores en la lista interna (caché).
     *
     * @return bool Devuelve **true** si hay errores o **false** de lo contrario.
     *
     * @see Errores::lista()
     */
    public function hay(): bool
    {
        return empty($this->errores);
    }

    /**
     * Obtiene una lista con todos los errores
     *
     * Genera un array con todos los errores ocurridos en los campos
     * almacenados internamente. Estos son asociados a los nombres de sus
     * propios campos.
     *
     * Recorre la lista de campos en búsqueda de errores y los almacena en la
     * caché si el estado **actualizarCache** está activa. Una vez hecho
     * desactiva el estado y cada vez que se llame a esta función devolverá el
     * contenido de la caché.
     *
     * @return array Devuelve un array con todos los errores
     *
     * @see Errores::actualizarCache()
     */
    public function lista(): array
    {
        if( $this->actualizarCache ) {
            $this->actualizarCache = false;

            $this->errores = array_map(function(Campo $campo) {
                return $campo->error()->mensaje();
            }, array_filter($this->campos, function(Campo $campo) {
                return $campo->error()->hay();
            }));
        }

        return $this->errores;
    }

    /**
     * Limpia los errores almacenados internamente
     *
     * Limpia la lista interna de errores (caché) y los errores almacenados en
     * los campos.
     */
    public function limpiar()
    {
        $this->errores = [];

        array_walk($this->campos, function(Campo $campo) {
            $campo->error()->limpiar();
        });
    }

    /**
     * Actualiza la caché de los mensajes de errores
     *
     * Interfamente activa el estado de actualizar caché lo que hará que la
     * próxima vez que se llae a la lista de errores revise todos los campos en
     * búsqueda de errores y los almacene en la caché.
     *
     * @see Errores::lista()
     */
    public function actualizarCache()
    {
        $this->actualizarCache = true;
    }

}
