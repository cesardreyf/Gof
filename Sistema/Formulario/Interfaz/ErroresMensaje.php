<?php

namespace Gof\Sistema\Formulario\Interfaz;

/**
 * Interfaz que almacena constantes con mensajes de errores
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
}
