<?php

namespace Gof\Sistema\Propiedades\Simple\Modulos;

use Gof\Gestor\Propiedades\Propiedad;
use Gof\Sistema\Propiedades\Simple\Interfaz\Obtenible;
use Gof\Sistema\Propiedades\Simple\Operaciones\OperacionCondicional;

/**
 * Módulo de obtención
 *
 * Esta clase extiende del operador condicional. Para más información ver la
 * documentación de dicha clase.
 *
 * @see OperacionCondicional para más información sobre el funcionamiento de este módulo.
 *
 * @package Gof\Sistema\Propiedades\Simple\Modulos
 */
class Obtencion extends OperacionCondicional
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct(
            new Propiedad(Obtenible::class),
            new ObtencionAccion()
        );
    }

    /**
     * Obtiene las propiedades
     *
     * Opera sobre todas las propiedades y llama al método **obtener** de cada una.
     *
     * @return bool Devuelve **true** en caso de éxito o **false** de lo contrario.
     *
     * @see Obtencion::errores() para ver los errores.
     */
    public function obtener(): bool
    {
        return $this->operar();
    }

}
