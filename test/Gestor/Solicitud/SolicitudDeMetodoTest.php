<?php

declare(strict_types=1);

namespace Test\Gestor\Solicitud;

use Gof\Gestor\Solicitud\Excepcion\ErrorDeTipo;
use Gof\Gestor\Solicitud\SolicitudDeMetodo;
use PHPUnit\Framework\TestCase;

class SolicitudDeMetodoTest extends TestCase
{

    public function testExisteConClaveExistente(): void
    {
        $datos = ['mi_clave' => 'valor'];
        $solicitud = new SolicitudDeMetodo($datos, 'POST');

        $existe = $solicitud->existe('mi_clave');

        $this->assertTrue($existe);
    }

    public function testExisteConClaveNoExistente(): void
    {
        $datos = ['otra_clave' => 'valor'];
        $solicitud = new SolicitudDeMetodo($datos, 'POST');

        $existe = $solicitud->existe('mi_clave');

        $this->assertFalse($existe);
    }

    public function testObtenerStringConValorExistente(): void
    {
        $datos = ['mi_string' => 'valor'];
        $solicitud = new SolicitudDeMetodo($datos, 'POST');

        $resultado = $solicitud->obtenerString('mi_string');

        $this->assertEquals('valor', $resultado);
    }

    public function testObtenerStringConValorNoExistente(): void
    {
        $datos = [];
        $solicitud = new SolicitudDeMetodo($datos, 'POST');

        $resultado = $solicitud->obtenerString('mi_string', 'default');

        $this->assertEquals('default', $resultado);
    }

    public function testObtenerIntConValorExistente(): void
    {
        $datos = ['mi_int' => '42'];
        $solicitud = new SolicitudDeMetodo($datos, 'POST');

        $resultado = $solicitud->obtenerInt('mi_int');

        $this->assertEquals(42, $resultado);
    }

    public function testObtenerIntConValorNoExistente(): void
    {
        $datos = [];
        $solicitud = new SolicitudDeMetodo($datos, 'POST');

        $resultado = $solicitud->obtenerInt('mi_int', 10);

        $this->assertEquals(10, $resultado);
    }

    public function testObtenerArrayConValorExistente(): void
    {
        $datos = ['mi_array' => [1, 2, 3]];
        $solicitud = new SolicitudDeMetodo($datos, 'POST');

        $resultado = $solicitud->obtenerArray('mi_array');

        $this->assertEquals([1, 2, 3], $resultado);
    }

    public function testObtenerArrayConValorNoExistente(): void
    {
        $datos = [];
        $solicitud = new SolicitudDeMetodo($datos, 'POST');

        $resultado = $solicitud->obtenerArray('mi_array', [4, 5, 6]);

        $this->assertEquals([4, 5, 6], $resultado);
    }

    public function testObtenerBoolConValorTrue(): void
    {
        $datos = ['mi_bool' => true];
        $solicitud = new SolicitudDeMetodo($datos, 'POST');

        $resultado = $solicitud->obtenerBool('mi_bool');

        $this->assertTrue($resultado);
    }

    public function testObtenerBoolConValorFalse(): void
    {
        $datos = ['mi_bool' => false];
        $solicitud = new SolicitudDeMetodo($datos, 'POST');

        $resultado = $solicitud->obtenerBool('mi_bool');

        $this->assertFalse($resultado);
    }

    public function testObtenerBoolConValorStringTrue(): void
    {
        $datos = ['mi_bool' => 'true'];
        $solicitud = new SolicitudDeMetodo($datos, 'POST');

        $resultado = $solicitud->obtenerBool('mi_bool');

        $this->assertTrue($resultado);
    }

    public function testObtenerBoolConValorStringFalse(): void
    {
        $datos = ['mi_bool' => 'false'];
        $solicitud = new SolicitudDeMetodo($datos, 'POST');

        $resultado = $solicitud->obtenerBool('mi_bool');

        $this->assertFalse($resultado);
    }

    public function testObtenerBoolConValorNoPermitido(): void
    {
        $datos = ['mi_bool' => 'no_valido'];
        $solicitud = new SolicitudDeMetodo($datos, 'POST');

        $this->expectException(ErrorDeTipo::class);
        $solicitud->obtenerBool('mi_bool');
    }

    public function testObtenerBoolConValorNoExistente(): void
    {
        $datos = [];
        $solicitud = new SolicitudDeMetodo($datos, 'POST');

        $resultado = $solicitud->obtenerBool('mi_bool', true);

        $this->assertTrue($resultado);
    }

}
