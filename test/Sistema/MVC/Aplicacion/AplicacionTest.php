<?php

declare(strict_types=1);

namespace Test\Sistema\MVC\Aplicacion;

use Gof\Gestor\Autoload\Autoload;
use Gof\Sistema\MVC\Aplicacion\Aplicacion;
use Gof\Sistema\MVC\Aplicacion\Excepcion\ControladorInexistente;
use Gof\Sistema\MVC\Aplicacion\Excepcion\ControladorInvalido;
use Gof\Sistema\MVC\Aplicacion\Interfaz\Controlador;
use Gof\Sistema\MVC\Aplicacion\Interfaz\Criterio;
use Gof\Sistema\MVC\Datos\Info;
use PHPUnit\Framework\TestCase;
use stdClass;

class AplicacionTest extends TestCase
{
    private Aplicacion $aplicacion;
    private Autoload $autoload;
    private Info $info;

    public function setUp(): void
    {
        $this->info = new Info();
        $this->autoload = $this->createMock(Autoload::class);
        $this->aplicacion = new Aplicacion($this->info, $this->autoload);
    }

    public function testEjecutarCreaUnaInstanciaTeniendoEnCuentaElNombreDelControladorEnInfo(): void
    {
        $nombreCompletoDelControlador = 'Controlador\Index';
        $this->info->controlador = $nombreCompletoDelControlador;
        $instanciaDelControlador = $this->createMock(Controlador::class);

        $this->autoload
            ->expects($this->once())
            ->method('instanciar')
            ->with($nombreCompletoDelControlador)
            ->willReturn($instanciaDelControlador);

        $this->assertSame($instanciaDelControlador, $this->aplicacion->ejecutar());
    }

    public function testEjecutarPasaElControladorAlCriterio(): void
    {
        $nombreCompletoDelControlador = 'Controlador\Perfil\Editar';
        $this->info->controlador = $nombreCompletoDelControlador;
        $this->info->parametros = ['nombre', 'genero', 'edad'];

        $instanciaDelControlador = $this->createMock(Controlador::class);
        $criterioQueEjecutaraElControlador = $this->createMock(Criterio::class);

        $this->autoload
            ->expects($this->once())
            ->method('instanciar')
            ->with($nombreCompletoDelControlador)
            ->willReturn($instanciaDelControlador);

        $instanciaDelControlador
            ->expects($this->once())
            ->method('parametros')
            ->with($this->info->parametros);

        $criterioQueEjecutaraElControlador
            ->expects($this->once())
            ->method('controlador')
            ->with($instanciaDelControlador);

        $criterioQueEjecutaraElControlador
            ->expects($this->once())
            ->method('ejecutar');

        $this->aplicacion->criterio = $criterioQueEjecutaraElControlador;
        $this->assertSame($instanciaDelControlador, $this->aplicacion->ejecutar());
    }

    public function testLanzarExcepcionSiElControladorEsNullAlEjecutarLaAplicacion(): void
    {
        $this->expectException(ControladorInexistente::class);
        $this->autoload
            ->expects($this->once())
            ->method('instanciar')
            ->willReturn(null);
        $this->aplicacion->ejecutar();
    }

    public function testLanzarExcepcionSiLaInstanciaDelObjetoNoImplementaLaInterfazControlador(): void
    {
        $this->expectException(ControladorInvalido::class);
        $noImplementoLaInterfazControlador = $this->createMock(stdClass::class);

        $this->autoload
            ->expects($this->once())
            ->method('instanciar')
            ->willReturn($noImplementoLaInterfazControlador);

        $this->aplicacion->ejecutar();
    }

    public function testEjecutarNoEjecutaElCriterioSiNoExiste(): void
    {
        $criterio = $this->createMock(Criterio::class);
        $this->assertNull($this->aplicacion->criterio);

        $this->aplicacion->criterio = $criterio;
        $this->assertInstanceOf(Criterio::class, $this->aplicacion->criterio);

        $this->aplicacion->criterio = null;
        $controlador = $this->createMock(Controlador::class);

        $this->autoload
            ->expects($this->once())
            ->method('instanciar')
            ->willReturn($controlador);

        $criterio
            ->expects($this->never())
            ->method('ejecutar')
            ->with($controlador);

        $this->assertSame($controlador, $this->aplicacion->ejecutar());
    }

    /**
     * @dataProvider dataArgumentosParaElControlador
     */
    public function testPasarArgumentosAlControladorAlMomentoDeInstanciarlo(array $argumentosEsperados)
    {
        $this->info->controlador = 'Cualquiera';
        $this->info->argumentos = $argumentosEsperados;

        $controlador = $this->createMock(Controlador::class);
        $this->aplicacion->namespaceDelControlador = 'Controlador\\';
        $nombreDelControlador = $this->aplicacion->namespaceDelControlador . $this->info->controlador;

        $this->autoload
            ->expects($this->once())
            ->method('instanciar')
            ->with($nombreDelControlador, ...$argumentosEsperados)
            ->willReturn($controlador);

        $this->assertSame($controlador, $this->aplicacion->ejecutar());
    }

    public function dataARgumentosParaElControlador(): array
    {
        return [
            [[
                'argumento1',
                'argumento2',
                PHP_INT_MAX,
                PHP_FLOAT_MAX,
                new stdClass(),
                ['algo' => 'bobo'],
            ]],
        ];
    }

}
