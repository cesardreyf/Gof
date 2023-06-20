<?php

namespace Gof\Sistema\Formulario\Validar;

use Gof\Sistema\Formulario\Interfaz\Campo;
use Gof\Sistema\Formulario\Interfaz\Campo\Validable;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Mediador\Campo\Error;

/**
 * Valida los límites de un campo de tipo float
 *
 * Valida que el valor de un campo esté dentro de los límites preestablecidos.
 *
 * @package Gof\Sistema\Formulario\Validar
 */
class ValidarLimiteFloat implements Validable
{
    /**
     * @var Campo $campo Instancia del campo a validar
     */
    private Campo $campo;

    /**
     * @var int Límite mínimo
     */
    private float $minimo = PHP_FLOAT_MIN;

    /**
     * @var int Límite máximo
     */
    private float $maximo = PHP_FLOAT_MAX;

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
        if( $this->campo->error()->hay() || !is_numeric($this->campo->valor()) ) {
            return null;
        }

        if( ($this->minimo > PHP_FLOAT_MIN) && ($this->campo->valor() < $this->minimo) ) {
            Error::reportar(
                $this->campo,
                "El límite mínimo es {$this->minimo}",
                Errores::ERROR_LIMITE_MINIMO_NO_ALCANZADO
            );
            return false;
        }

        if( ($this->maximo > $this->minimo) && ($this->campo->valor() > $this->maximo) ) {
            Error::reportar(
                $this->campo,
                "El límite máximo es {$this->maximo}",
                Errores::ERROR_LIMITE_MAXIMO_EXCEDIDO
            );
            return false;
        }

        return true;
    }

    /**
     * Obtiene o define el límite mínimo
     *
     * @param ?float $limite Límite mínimo o **null** para obtener el actual.
     *
     * @return float Devuelve el límite mínimo actual.
     */
    public function minimo(?float $limite = null): float
    {
        return $limite === null ? $this->minimo : $this->minimo = $limite;
    }

    /**
     * Obtiene o define el límite máximo
     *
     * @param ?float $limite Límite máximo o **null** para obtener el actual.
     *
     * @return float Devuelve el límite máximo actual.
     */
    public function maximo(?float $limite = null): float
    {
        return $limite === null ? $this->maximo : $this->maximo = $limite;
    }

}
