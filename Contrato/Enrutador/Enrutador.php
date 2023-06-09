<?php

namespace Gof\Contrato\Enrutador;

use Gof\Interfaz\Lista\Textos as Lista;

/**
 * Interfaz para clases que devuelvan el nombre de una clase válido para instanciar
 *
 * @package Gof\Contrato\Enrutador
 */
interface Enrutador
{
    /**
     * Procesa la solicitud
     *
     * @param Lista $solicitud Solicitud a procesar
     *
     * @return bool Devuelve el estado del procesamiento.
     */
    public function procesar(Lista $solicitud): bool;

    /**
     * Devuelve el nombre completo de la clase
     *
     * Obtiene el nombre de la clase y su respectivo namespace en base al criterio
     * de la implementación de la interfaz Enrutador.
     *
     * @return string Devulve el nombre de la clase junto a su namespace
     */
    public function nombreClase(): string;

    /**
     * Obtiene el resto
     *
     * El enrutador obtiene un nombre de clase basado en una entrada de datos, el cual puede
     * ser una consulta con los recursos solicitado, una vez hallado el recurso y obtenido el
     * nombre de la clase el resto es el sobrante de lo solicitado. En URL Amigables el resto
     * serían los parámetros.
     *
     * @return array Devuelve el resto de la solicitud.
     */
    public function resto(): array;
}
