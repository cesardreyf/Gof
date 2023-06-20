<?php

namespace Gof\Sistema\Formulario\Validar;

use Gof\Sistema\Formulario\Interfaz\Campo;
use Gof\Sistema\Formulario\Interfaz\Campo\Validable;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Mediador\Campo\Error;

class ValidarLongitud implements Validable
{
    /**
     * @var Campo Instancia del campo
     */
    private Campo $campo;

    /**
     * @var int Longitud mínimo permitido
     */
    private int $minimo = 0;

    /**
     * @var int Longitud máximo permitido
     */
    private int $maximo = PHP_INT_MAX;

    /**
     * Constructor
     *
     * @param Campo $campo Instancia del campo a validar.
     */
    public function __construct(Campo $campo)
    {
        $this->campo = $campo;
    }

    /**
     * Valida la longitud del valor del campo
     *
     * Verifica que la longitud del valor del campo esté dentro de los límites
     * establecidos.
     *
     * Si existen errores en el campo o el valor del campo no es un string la
     * función devolverá un valor **null**.
     *
     * @return ?bool Devuelve **true** en caso de éxito o **false** de lo contrario.
     */
    public function validar(): ?bool
    {
        if( $this->campo->error()->hay() || !is_string($this->campo->valor()) ) {
            return null;
        }

        $longitud = mb_strlen($this->campo->valor());

        if( $this->minimo > 0 && $longitud < $this->minimo ) {
            Error::reportar(
                $this->campo,
                "La longitud mínima es de {$this->minimo} caracteres",
                Errores::ERROR_LONGITUD_MINIMO_NO_ALCANZADO
            );
            return false;
        }

        if( $this->maximo > $this->minimo && $longitud > $this->maximo ) {
            Error::reportar(
                $this->campo,
                "La longitud máxima es de {$this->maximo} caracteres",
                Errores::ERROR_LONGITUD_MAXIMO_EXCEDIDO
            );
            return false;
        }

        return true;
    }

    /**
     * Obtiene o define la longitud mínima
     *
     * @param ?int $longitud Longitud mínima o **null** para obtener el actual.
     *
     * @return int Devuelve la longitud mínima actual.
     */
    public function minimo(?int $longitud = null): int
    {
        return $longitud === null ? $this->minimo : $this->minimo = $longitud;
    }

    /**
     * Obtiene o define la longitud máxima
     *
     * @param ?int $longitud Longitud máxima o **null** para obtener el actual.
     *
     * @return int Devuelve la longitud máxima actual.
     */
    public function maximo(?int $longitud = null): int
    {
        return $longitud === null ? $this->maximo : $this->maximo = $longitud;
    }

}
