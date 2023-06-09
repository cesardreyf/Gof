<?php

namespace Gof\Sistema\Formulario\Validar;

use Gof\Sistema\Formulario\Interfaz\Campo;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Mediador\Campo\Error;

/**
 * Valida si un campo existe dentro de los datos recibidos del formulario
 *
 * Verifica que el nombre del campo exista dentro del array de datos del formulario.
 *
 * @package Gof\Sistema\Formulario\Validar
 */
class ValidarExistencia
{
    /**
     * @var string Mensaje de error que indica que el campo está vacío.
     */
    public const ERROR_MENSAJE = 'Campo vacío';

    /**
     * @var bool Almacena el estado de la existencia del campo.
     */
    private bool $existe = true;

    /**
     * Constructor
     *
     * @param Campo $campo Instancia del campo a validar.
     * @param array $datos Datos del formulario de referencia.
     */
    public function __construct(Campo $campo, array $datos)
    {
        if( isset($datos[$campo->clave()]) === false ) {
            Error::reportar($campo, self::ERROR_MENSAJE, Errores::ERROR_CAMPO_INEXISTENTE);
            $this->existe = false;
        }
    }

    /**
     * Indica si existe o no el campo en los datos recibidos del formulario
     *
     * @return bool Devuelve **true** si el campo existe o **false** de lo contrario.
     */
    public function existe(): bool
    {
        return $this->existe;
    }

}
