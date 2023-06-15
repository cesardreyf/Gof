<?php

namespace Gof\Sistema\Formulario\Interfaz;

/**
 * Interfaz que almacena constantes con mensajes de errores
 *
 * Interfaz que contiene constantes con mensajes de errores comunes para el
 * sistema de formulario.
 *
 * @package Gof\Sistema\Formulario\Interfaz
 */
interface ErroresMensaje
{
    /**
     * @var string Mensaje de error para cuando el tipo del campo no es un **string**.
     */
    public const NO_ES_STRING = 'Se esperaba un valor de tipo string';

    /**
     * @var string Mensaje de error para cuando el tipo del campo no es un **int**.
     */
    public const NO_ES_INT = 'Se esperaba un valor de tipo numérico';

    /**
     * @var string Mensaje de error para cuando el tipo del campo no es un **array**.
     */
    public const NO_ES_ARRAY = 'Se esperaba un valor de tipo array';

    /**
     * @var string Mensaje de error para cuando el tipo del campo no es una tabla.
     */
    public const NO_ES_TABLA = 'Se esperaba un valor de tipo array de arrays';

    /**
     * @var string Mensaje de error para cuando el tipo del campo no es un **float**.
     */
    public const NO_ES_FLOAT = 'Se esperaba un valor de tipo float';

    /**
     + @var string Mensaje de error para cuando el tipo del campo no es un **select**.
     */
    public const NO_ES_SELECT = 'Se esperaba un valor de tipo string';

    /**
     * @var string Mensaje de error para cuando el campo está vacío.
     */
    public const CAMPO_VACIO = 'Campo vacío';
}
