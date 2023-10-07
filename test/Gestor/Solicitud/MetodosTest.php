<?php

declare(strict_types=1);

namespace Test\Gestor\Solicitud;

use Gof\Gestor\Solicitud\Excepcion\MetodoHttpInexistente;
use Gof\Gestor\Solicitud\Excepcion\MetodoHttpInvalido;
use Gof\Gestor\Solicitud\Metodos;
use Gof\Gestor\Solicitud\SolicitudDeMetodo;
use PHPUnit\Framework\TestCase;

class MetodosTest extends TestCase
{

    public function testGetMetodo(): void
    {
        $metodos = new Metodos([], ['clave' => 'valor'], [], [], []);

        $solicitud = $metodos->get();

        $this->assertInstanceOf(SolicitudDeMetodo::class, $solicitud);
    }

    public function testPostMetodo(): void
    {
        $metodos = new Metodos([], ['clave' => 'valor'], [], [], []);

        $solicitud = $metodos->post();

        $this->assertInstanceOf(SolicitudDeMetodo::class, $solicitud);
    }

    public function testElegirMetodoExistente(): void
    {
        $server = ['REQUEST_METHOD' => 'GET'];
        $metodos = new Metodos([], [], $server);

        $metodo = $metodos->elegido();

        $this->assertEquals('GET', $metodo);
    }

    public function testElegirMetodoNoExistente(): void
    {
        $server = [];
        $metodos = new Metodos([], [], [], [], $server);

        $this->expectException(MetodoHttpInexistente::class);
        $metodos->elegido();
    }

    public function testElegirMetodoInvalido(): void
    {
        $server = ['REQUEST_METHOD' => 'INVALID'];
        $metodos = new Metodos([], [], $server);

        $this->expectException(MetodoHttpInvalido::class);
        $metodos->elegido();
    }

}
