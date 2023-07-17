<?php

declare(strict_types=1);

namespace Test\Sistema\MVC\Controlador;

use Gof\Gestor\Autoload\Autoload;
use Gof\Sistema\MVC\Aplicacion\DAP\DAP;
use Gof\Sistema\MVC\Aplicacion\DAP\N1 as DAPN1;
use Gof\Sistema\MVC\Controlador\Ejecutor;
use Gof\Sistema\MVC\Controlador\Excepcion\ControladorInexistente;
use Gof\Sistema\MVC\Controlador\Excepcion\ControladorInvalido;
use Gof\Sistema\MVC\Interfaz\Ejecutable;
use PHPUnit\Framework\TestCase;
use stdClass;

class EjecutorTest extends TestCase
{
    private Ejecutor $ejecutor;
    private Autoload $autoload;
    private DAPN1 $dapn1;

    public function setUp(): void
    {
        $this->dapn1 = new DAPN1();
        $this->dap = $this->createMock(DAP::class);
        $this->autoload = $this->createMock(Autoload::class);
        $this->ejecutor = new Ejecutor($this->autoload, $this->dapn1);
    }

    public function testEjecutarCreaUnaInstanciaDeLaClaseAlmacenadaEnDAPN1(): void
    {
        $this->dapn1->controlador = 'Controlador\Index';
        $instanciaDelControlador = $this->createMock(Ejecutable::class);
        $this->autoload
            ->expects($this->once())
            ->method('instanciar')
            ->with($this->dapn1->controlador)
            ->willReturn($instanciaDelControlador);
        $this->ejecutor->ejecutar($this->dap);
    }

    public function testEjecutarCreaUnaInstanciaDeLaClaseYPasaLosArgumentosAlmacenadosEnDAPN1(): void
    {
        $this->dapn1->controlador = 'Controlador\Index';
        $this->dapn1->argumentos = ['argumento1', 'argumento2'];
        $instanciaDelControlador = $this->createMock(Ejecutable::class);
        $this->autoload
            ->expects($this->once())
            ->method('instanciar')
            ->with($this->dapn1->controlador, ...$this->dapn1->argumentos)
            ->willReturn($instanciaDelControlador);
        $this->ejecutor->ejecutar($this->dap);
    }

    public function testLanzarExcepcionSiNoSeCreaLaInstancia(): void
    {
        $this->expectException(ControladorInexistente::class);
        $this->autoload
            ->expects($this->once())
            ->method('instanciar')
            ->willReturn(null);
        $this->ejecutor->ejecutar($this->dap);
    }

    public function testLanzarExcepcionSiLaClaseInstanciadaNoEsUnEjecutable(): void
    {
        $this->expectException(ControladorInvalido::class);
        $instancia = $this->createMock(stdClass::class);
        $this->autoload
            ->expects($this->once())
            ->method('instanciar')
            ->willReturn($instancia);
        $this->ejecutor->ejecutar($this->dap);
    }

    public function testEjecutarCreaLaInstanciaDelControladorYLoEjecuta(): void
    {
        $this->dapn1->controlador = 'Controlador\Index';
        $this->dapn1->argumentos = ['argumento1', 'argumento2'];
        $instanciaDelControlador = $this->createMock(Ejecutable::class);
        $this->autoload
            ->expects($this->once())
            ->method('instanciar')
            ->with($this->dapn1->controlador, ...$this->dapn1->argumentos)
            ->willReturn($instanciaDelControlador);
        $instanciaDelControlador
            ->expects($this->once())
            ->method('ejecutar')
            ->with($this->dap);
        $this->ejecutor->ejecutar($this->dap);
    }

}
