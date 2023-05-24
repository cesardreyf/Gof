<?php

namespace Gof\Sistema\Formulario\Interfaz;

/**
 * Interfaz para los tipos de campos
 *
 * @package Gof\Sistema\Formulario\Interfaz
 */
interface Tipos
{
    /**
     * @var int Define que el tipo del campo debe ser **string**.
     */
    public const TIPO_STRING = 0;

    /**
     * @var int Define que el tipo del campo debe ser **integer**.
     */
    public const TIPO_INT = 1;

    /**
     * @var int Define que el tipo del campo debe ser un **array** (select).
     */
    public const TIPO_ARRAY = 2;
}

