<?php

namespace Gof\Gestor\Redireccion;

/**
 * Gestor de redirecciones simple
 *
 * Clase encargada de enviar cabeceras HTTP al cliente para redirigirlo a otra dirección.
 * El gestor es básico.
 *
 * @package Gof\Redireccion
 */
class Redirigir
{
    /**
     * @var string Almacena la dirección principal.
     */
    private string $paginaPrincipal;

    /**
     * Constructor
     *
     * @param string $principal URL base para las redirecciones.
     */
    public function __construct(string $principal = '')
    {
        if( empty($principal) ) {
            $protocolo = $_SERVER['SERVER_PORT'] === '443' ? 'https://' : 'http://';
            $servidor = empty($_SERVER['HTTP_HOST']) ? $_SERVER['SERVER_NAME'] : $_SERVER['HTTP_HOST'];
            $this->base($protocolo . $servidor);
        } else {
            $this->base($principal);
        }
    }

    /**
     * Redirige a la página principal
     */
    public function paginaPrincipal()
    {
        $this->a('');
    }

    /**
     * Redirige a una página específica
     *
     * Teniendo en cuenta la página principal, envía una cabecera HTTP al cliente para redirigirlo
     * a otra dirección.
     *
     * @param string $direccion           Dirección a la que se redirigirá (se agrega después de la dirección base).
     * @param bool   $redireccionTemporal Indica si la redirección será temporal. En caso de **false** será una redirección permanente.
     */
    public function a(string $direccion, bool $redireccionTemporal = true)
    {
        $codigoEstado = $redireccionTemporal ? 302 : 301;

        if( isset($_SERVER['REQUEST_METHOD']) && in_array($_SERVER['REQUEST_METHOD'], ['POST', 'PUT', 'DELETE']) ) {
            $codigoEstado = 303;
        }

        header("Location: {$this->paginaPrincipal}{$direccion}", true, $codigoEstado);
    }

    /**
     * Obtiene o define la dirección base
     *
     * @param ?string $base **NULL** para obtener la dirección base actual o un **string** para definir el nuevo.
     *
     * @return string Devuelve la dirección base actual.
     */
    public function base(?string $base = null): string
    {
        if( $base === null ) {
            return $this->paginaPrincipal;
        }

        return $this->paginaPrincipal = rtrim($base, '/') . '/';
    }

}
