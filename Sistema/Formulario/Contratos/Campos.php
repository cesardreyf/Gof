<?php

namespace Gof\Sistema\Formulario\Contratos;

use Gof\Interfaz\Lista;
use Gof\Sistema\Formulario\Interfaz\Campo;

/**
 * Contrato a emplear por el gestor de campos del sistema de formulario
 *
 * Interfaz que debe implementar la clase encargada de gestionar la validación
 * de los campos.
 *
 * @package Gof\Sistema\Formulario\Contratos
 */
interface Campos extends Lista
{
    /**
     * Crea un campo
     *
     * @param string $nombreDelCampo Nombre del campo a crear.
     * @param int    $tipoDeDato     Tipo de dato del campo.
     *
     * @return Campo Instancia del campo creado.
     */
    public function crear(string $nombreDelCampo, int $tipoDeDato): Campo;

    /**
     * Obtiene un campo
     *
     * @return ?Campo Obtiene la instancia del campo o **null** en caso de error.
     */
    public function obtener(string $nombreDelCampo): ?Campo;

    /**
     * Valida todos los campos creados
     *
     * @return bool Devuelve el estado de la validación.
     */
    public function validar(): bool;

    /**
     * Limpia el buffer de campos
     *
     * Vacía la lista de campos almacenados internamente.
     */
    public function limpiar();
}
