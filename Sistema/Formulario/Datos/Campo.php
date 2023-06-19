<?php

namespace Gof\Sistema\Formulario\Datos;

use Gof\Datos\Errores\Mensajes\Error;
use Gof\Sistema\Formulario\Interfaz\Campo as ICampo;
use Gof\Sistema\Formulario\Interfaz\Campo\Validable;

/**
 * Dato de tipo campo para almacenar información
 *
 * Clase creada para el sistema de formularios que almacena información típica y básica de
 * un campo de un formulario como: clave, valor, tipo y errores.
 *
 * @package Gof\Sistema\Formulario\Datos
 */
class Campo implements ICampo
{
    /**
     * Clave asociada al campo
     *
     * En un formulario sería el valor del atributo *name* de un campo.
     *
     * @var string
     */
    public string $clave;

    /**
     * @var int Tipo de dato.
     */
    public int $tipo;

    /**
     * @var Error Almacena el o los errores asociados al campo.
     */
    public Error $error;

    /**
     * @var mixed Valor del campo
     */
    public mixed $valor;

    /**
     * @var array<string, Validable> Lista de validaciones extra.
     */
    protected array $vextra = [];

    /**
     * Constructor
     *
     * @param string $clave Clave asociada al campo del formulario.
     * @param int    $tipo  Tipo de dato del campo.
     */
    public function __construct(string $clave, int $tipo = 0)
    {
        $this->valor = null;
        $this->tipo  = $tipo;
        $this->clave = $clave;
        $this->error = new Error();
    }

    /**
     * Clave asociada al campo
     *
     * @return string Devuelve el nombre del campo.
     */
    public function clave(): string
    {
        return $this->clave;
    }

    /**
     * Obtiene el tipo de dato del campo
     *
     * @return int Devuelve el tipo de dato.
     */
    public function tipo(): int
    {
        return $this->tipo;
    }

    /**
     * Obtiene el valor del campo
     *
     * @return mixed Devuelve el valor que tiene el campo.
     */
    public function valor(): mixed
    {
        return $this->valor;
    }

    /**
     * Lista de errores
     *
     * Almacena errores asociados al campo.
     *
     * @return Error Devuelve una instancia de la lista interna de errores.
     */
    public function error(): Error
    {
        return $this->error;
    }

    public function validar(): ?bool
    {
        return false;
    }

    /**
     * Agrega una nueva validación al campo
     *
     * Agrega a la cola de validaciones del campo una nueva validación. Si ya
     * existe simplemente obtiene una instancia de la misma.
     *
     * Si no existe el validador reservado se crea una instancia y se le pasa
     * por el constructor la instancia del campo.
     *
     * @param string $validador Nombre completo de la clase.
     *
     * @return Validable
     */
    public function validador(string $validador): Validable
    {
        if( !isset($this->vextra[$validador]) ) {
            // TAREA
            //  Validar que lo que se está pasando sea una clase
            $this->vextra[$validador] = new $validador($this);
        }

        return $this->vextra[$validador];
    }

    /**
     * Obtiene la lista de validaciones extras
     *
     * @return array<string, Validable>
     */
    public function vextra(): array
    {
        return $this->vextra;
    }

}
