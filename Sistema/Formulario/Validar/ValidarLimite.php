<?php

namespace Gof\Sistema\Formulario\Validar;

use Gof\Sistema\Formulario\Interfaz\Campo;
use Gof\Sistema\Formulario\Interfaz\Campo\Validable;
use Gof\Sistema\Mediador\Campo\Error;

/**
 * Valida los límites de un campo de tipo int
 *
 * Valida que el valor de un campo esté dentro de los límites preestablecidos.
 *
 * @package Gof\Sistema\Formulario\Validar
 */
class ValidarLimite implements Validable
{
    /**
     * @var Campo $campo Instancia del campo a validar
     */
    private Campo $campo;

    /**
     * @var int Límite mínimo
     */
    private int $minimo = PHP_INT_MIN;

    /**
     * @var int Límite máximo
     */
    private int $maximo = PHP_INT_MAX;

    /**
     * Constructor
     *
     * @param Campo $campo Campo a validar
     */
    public function __construct(Campo $campo)
    {
        $this->campo = $campo;
    }

    /**
     * Valida que el valor del campo esté dentro de los límites
     *
     * @return ?bool Devuelve **true** en caso de éxito o **false** de lo contrario.
     */
    public function validar(): ?bool
    {
        if( $this->campo->error()->hay() ) {
            return null;
        }

        if( !is_numeric($this->campo->valor()) ) {
            return false;
        }

        if( $this->minimo > PHP_INT_MIN && $this->campo->valor() < $this->minimo ) {
            // Errores temporal
            Error::reportar($this->campo, 1, "El límite mínimo es {$this->minimo}");
            return false;
        }

        if( $this->maximo > $this->minimo && $this->campo->valor() > $this->maximo ) {
            // Errores temporal
            Error:reportar($this->campo, 2, "El límite máximo es {$this->maximo}");
            return false;
        }

        return true;
    }

    /**
     * Obtiene o define el límite mínimo
     *
     * @param ?int $limite Límite mínimo o **null** para obtener el actual.
     *
     * @return int Devuelve el límite mínimo actual.
     */
    public function minimo(?int $limite = null): int
    {
        return $limite === null ? $this->minimo : $this->minimo = $limite;
    }

    /**
     * Obtiene o define el límite máximo
     *
     * @param ?int $limite Límite máximo o **null** para obtener el actual.
     *
     * @return int Devuelve el límite máximo actual.
     */
    public function maximo(?int $limite = null): int
    {
        return $limite === null ? $this->maximo : $this->maximo = $limite;
    }

}
