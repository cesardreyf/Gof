<?php

namespace Gof\Sistema\Formulario\Gestor;

/**
 * Gestor de datos y estados del sistema
 *
 * Clase encargada de almacenar datos y estados que modifican el comportamiento
 * del sistema.
 *
 * @package Gof\Sistema\Formulario\Gestor
 */
class Sistema
{
    /**
     * @var array Lista de campos
     */
    public array $campos = [];

    /**
     * @var array Datos del formulario
     */
    public array $datos = [];

    /**
     * @var bool Estado que indica que se debe actualizar la caché de errores
     */
    public bool $actualizarCache = true;
}
