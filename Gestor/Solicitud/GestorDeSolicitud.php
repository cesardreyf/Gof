<?php

namespace Gof\Gestor\Solicitud;

/**
 * Gestor de solicitudes
 *
 * @package Gof\Gestor\Solicitud
 */
class GestorDeSolicitud
{

    /**
     * @var Metodos
     */
    private Metodos $metodos;

    /**
     * Constructor
     *
     * @param array $get
     * @param array $post
     * @param array $server
     */
    public function __construct(array $get, array $post, array $server)
    {
        $this->metodos = new Metodos($get, $post, $server);
    }

    /**
     * Obtiene el nombre del método HTTP elegido
     *
     * @return string
     */
    public function metodo(): string
    {
        return $this->metodos->elegido();
    }

    /**
     * Accede a los métodos disponibles por el gestor de solicitudes
     *
     * @return Metodos
     */
    public function desde(): Metodos
    {
        return $this->metodos;
    }

}
