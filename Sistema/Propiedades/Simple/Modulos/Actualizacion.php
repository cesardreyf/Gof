<?php

namespace Gof\Sistema\Propiedades\Simple\Modulos;

use Gof\Gestor\Propiedades\Propiedad;
use Gof\Sistema\Propiedades\Simple\Interfaz\Actualizable;
use Gof\Sistema\Propiedades\Simple\Operaciones\OperacionCondicional;

/**
 * Módulo de actualización
 *
 * Esta clase extiende del operador condicional. Para más información ver la
 * documentación de dicha clase.
 *
 * @see OperacionCondicional para más información sobre el funcionamiento de este módulo.
 *
 * @package Gof\Sistema\Propiedades\Simple\Modulos
 */
class Actualizacion extends OperacionCondicional
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct(
            new Propiedad(Actualizable::class),
            new ActualizacionAccion()
        );
    }

    /**
     * Actualiza las propiedades
     *
     * Opera sobre todas las propiedades y llama al método **actualizar** de cada una.
     *
     * @return bool Devuelve **true** en caso de éxito o **false** de lo contrario.
     *
     * @see Actualizacion::errores() para ver los errores.
     */
    public function actualizar(): bool
    {
        return $this->operar();
    }

}
