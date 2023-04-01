<?php

namespace Gof\Sistema\Propiedades\Simple\Modulos;

use Gof\Gestor\Propiedades\Propiedad;
use Gof\Sistema\Propiedades\Simple\Interfaz\Persistible;
use Gof\Sistema\Propiedades\Simple\Operaciones\OperacionSimple;

/**
 * Módulo de persistencia
 *
 * Esta clase extiende del operador simple. Para más información ver la
 * documentación de dicha clase.
 *
 * @see OperacionSimple para más información sobre el funcionamiento de este módulo.
 *
 * @package Gof\Sistema\Propiedades\Simple\Modulos
 */
class Persistencia extends OperacionSimple
{

    public function __construct()
    {
        parent::__construct(
            new Propiedad(Persistible::class),
            new PersistenciaAccion()
        );
    }

    /**
     * Persiste las propiedades
     *
     * Opera sobre todas las propiedades y llama al método **persistir** de cada una.
     *
     * @return bool Devuelve **true** en caso de éxito o **false** de lo contrario.
     *
     * @see Persistencia::errores() para ver los errores.
     */
    public function persistir(): bool
    {
        return $this->operar();
    }

}
