<?php

declare(strict_types=1);

namespace Test\Gestor\Solicitud;

use Gof\Gestor\Solicitud\GestorDeSolicitud;
use Gof\Gestor\Solicitud\Metodos;
use PHPUnit\Framework\TestCase;

class GestorDeSolicitudTest extends TestCase
{

    public function testMetodo(): void
    {
        $server = ['REQUEST_METHOD' => 'GET'];
        $gestor = new GestorDeSolicitud([], [], $server);

        $metodo = $gestor->metodo();

        $this->assertEquals('GET', $metodo);
    }

    public function testDesde(): void
    {
        $gestor = new GestorDeSolicitud([], [], []);

        $metodos = $gestor->desde();

        $this->assertInstanceOf(Metodos::class, $metodos);
    }

}
