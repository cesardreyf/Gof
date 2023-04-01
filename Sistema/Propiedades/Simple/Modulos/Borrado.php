<?php

namespace Gof\Sistema\Propiedades\Simple\Modulos;

use Gof\Gestor\Propiedades\Propiedad;
use Gof\Sistema\Propiedades\Simple\Interfaz\Borrable;
use Gof\Sistema\Propiedades\Simple\Operaciones\OperacionCondicional;

/**
 * Módulo de borrado
 *
 * Esta clase extiende del operador condicional. Para más información ver la
 * documentación de dicha clase.
 *
 * @see OperacionCondicional para más información sobre el funcionamiento de este módulo.
 *
 * @package Gof\Sistema\Propiedades\Simple\Modulos
 */
class Borrado extends OperacionCondicional
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct(
            new Propiedad(Borrable::class),
            new BorradoAccion(),
        );
    }

    /**
     * Borra las propiedades
     *
     * Opera sobre todas las propiedades y llama al método **borrar** de cada una.
     *
     * @return bool Devuelve **true** en caso de éxito o **false** de lo contrario.
     *
     * @see Borrado::errores() para ver los errores.
     */
    public function borrar(): bool
    {
        return $this->operar();
    }

}
