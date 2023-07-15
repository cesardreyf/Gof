<?php

declare(strict_types=1);

namespace Test\Sistema\MVC\Rutas;

use Gof\Contrato\Enrutador\Enrutador;
use Gof\Gestor\Url\Amigable\GestorUrl;
use Gof\Sistema\MVC\Aplicacion\DAP\N1 as DAP;
use Gof\Sistema\MVC\Interfaz\Ejecutable;
use Gof\Sistema\MVC\Rutas\Configuracion;
use Gof\Sistema\MVC\Rutas\Excepcion\ConfiguracionInexistente;
use Gof\Sistema\MVC\Rutas\Excepcion\EnrutadorInexistente;
use Gof\Sistema\MVC\Rutas\Rutas;
use Gof\Sistema\MVC\Rutas\Simple\Gestor as GestorSimple;
use PHPUnit\Framework\TestCase;

class RutasTest extends TestCase
{
    private DAP $dap;
    private Rutas $rutas;
    private Configuracion $configuracion;

    public function setUp(): void
    {
        $this->dap = new DAP();
        $this->rutas = new Rutas();
        $this->configuracion = new Configuracion();
        $this->rutas->configuracion($this->configuracion);
    }

    public function testMetodoGestorReflejaLaInstanciaDelEnrutadorEnConfiguracion(): void
    {
        $enrutador = $this->createMock(Enrutador::class);
        $this->configuracion->enrutador = $enrutador;
        $this->assertSame($enrutador, $this->rutas->gestor());
    }

    public function testMetodoConfiguracionDevuelveNullAlInstanciar(): void
    {
        $rutas = new Rutas();
        $this->assertNull($rutas->configuracion());
    }

    public function testDefinirConfiguracion(): void
    {
        $rutas = new Rutas();
        $nuevaConfiguracion = new Configuracion();
        $this->assertNull($rutas->configuracion());
        $rutas->configuracion($nuevaConfiguracion);
        $this->assertSame($nuevaConfiguracion, $rutas->configuracion());
    }

    public function testEjecutarSinUnaConfiguracionLanzaExcepcion(): void
    {
        $this->expectException(ConfiguracionInexistente::class);
        $rutas = new Rutas();
        $this->assertNull($rutas->configuracion());
        $rutas->ejecutar($this->dap);
    }

    public function testFuncionamientoEsperado()
    {
        $this->configuracion->separador = '/';
        $this->configuracion->urlClave = '__peticion';
        $_GET[$this->configuracion->urlClave] = 'recurso1/recurso2';

        $enrutador = $this->createMock(Enrutador::class);
        $this->configuracion->enrutador = $enrutador;

        $separador = $this->configuracion->separador;
        $solicitud = $_GET[$this->configuracion->urlClave];
        $solicitudProcesado = new GestorUrl($solicitud, $separador);

        $resto = ['param1', 'param2'];
        $nombreClase = 'Controlador\Index';

        $this->assertEmpty($this->dap->parametros);
        $this->assertEmpty($this->dap->controlador);

        $enrutador
            ->expects($this->once())
            ->method('procesar')
            ->with($solicitudProcesado->lista());
        $enrutador
            ->expects($this->once())
            ->method('resto')
            ->willReturn($resto);
        $enrutador
            ->expects($this->once())
            ->method('nombreClase')
            ->willReturn($nombreClase);

        $this->rutas->ejecutar($this->dap);
        $this->assertSame($resto, $this->dap->parametros);
        $this->assertSame($nombreClase, $this->dap->controlador);
    }

}
