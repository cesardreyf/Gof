<?php

namespace Gof\Gestor\Solicitud;

use Gof\Gestor\Solicitud\Excepcion\MetodoHttpInexistente;
use Gof\Gestor\Solicitud\Excepcion\MetodoHttpInvalido;

class Metodos
{

    /**
     * @var array
     */
    public const METODOS_VALIDOS = [
        'GET', 'POST', 'PUT', 'DELETE', 'HEAD', 'PATCH', 'OPTIONS'
    ];

    public const REQUEST_METHOD = 'REQUEST_METHOD';

    /**
     * @var string Cachea el método elegido por el cliente
     */
    private ?string $metodoElegido = null;

    /**
     * @var SolicitudDeMetodo
     */
    private SolicitudDeMetodo $metodoGet;

    /**
     * @var SolicitudDeMetodo
     */
    private SolicitudDeMetodo $metodoPost;

    /**
     * Constructor
     *
     * @param array $get
     * @param array $post
     * @param array $server
     */
    public function __construct(
        private array $get,
        private array $post,
        private array $server
    )
    {
    }

    public function get(): SolicitudDeMetodo
    {
        return $this->metodoGet ?? $this->metodoGet = SolicitudDeMetodo::desdeArray($this->get, 'GET');
    }

    public function post(): SolicitudDeMetodo
    {
        return $this->metodoPost ?? $this->metodoPost = SolicitudDeMetodo::desdeArray($this->post, 'POST');
    }

    public function elegido(): string
    {
        if( !is_null($this->metodoElegido) ) {
            return $this->metodoElegido;
        }

        if( !isset($this->server[self::REQUEST_METHOD]) ) {
            throw new MetodoHttpInexistente();
        }

        $metodo = $this->server[self::REQUEST_METHOD];
        if( !in_array($metodo, self::METODOS_VALIDOS) ) {
            throw new MetodoHttpInvalido();
        }

        return $this->metodoElegido = $metodo;
    }

}
