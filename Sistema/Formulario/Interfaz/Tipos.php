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
     * @var int Define que el tipo del campo debe ser un **float**.
     */
    public const TIPO_FLOAT = 2;

    /**
     * @var int Define que el tipo del campo debe ser un **array**.
     */
    public const TIPO_ARRAY = 3;

    /**
     * @var int Define que el tipo del campo debe ser una **tabla** (array de arrays).
     */
    public const TIPO_TABLA = 4;

    /**
     * @var int Define que el tipo del campo debe ser un **select**.
     */
    public const TIPO_SELECT = 5;

    /**
     * @var int Define que el tipo del campo debe ser un **bool**.
     */
    public const TIPO_BOOL = 6;
}

