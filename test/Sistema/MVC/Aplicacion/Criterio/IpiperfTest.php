<?php

declare(strict_types=1);

namespace Test\Sistema\MVC\Aplicacion\Criterio;

use Gof\Sistema\MVC\Aplicacion\Criterio\Ipiperf;
use Gof\Sistema\MVC\Aplicacion\Criterio\Ipiperf\Datos\Registros;
use Gof\Sistema\MVC\Aplicacion\Criterio\Ipiperf\Interfaz\Controlador;
use Gof\Sistema\MVC\Aplicacion\Excepcion\ControladorInvalido;
use Gof\Sistema\MVC\Aplicacion\Interfaz\Controlador as ControladorBasico;
use Gof\Sistema\MVC\Aplicacion\Interfaz\Criterio;
use Gof\Sistema\MVC\Interfaz\Ejecutable;
use PHPUnit\Framework\TestCase;

class IpiperfTest extends TestCase
{
    private Ipiperf $criterio;

    public function setUp(): void
    {
        $this->criterio = new Ipiperf();
    }

    public function testImplementarInterfazCriterioDeLaAplicacion(): void
    {
        $this->assertInstanceOf(Criterio::class, $this->criterio);
    }

    public function testEsUnEjecutable(): void
    {
        $this->assertInstanceOf(Ejecutable::class, $this->criterio);
    }

    public function testLanzarExcepcionSiElControladorRecibidoNoImplementaLaInterfazControlador(): void
    {
        $this->expectException(ControladorInvalido::class);
        $controlador = $this->createMock(ControladorBasico::class);
        $this->criterio->controlador($controlador);
    }

    public function testLaFuncionEjecutarControladorEsUnAliasDeEjecutar(): void
    {
        $controlador = $this->createMock(Controlador::class);
        $criterio = $this->getMockBuilder(Ipiperf::class)
           ->setMethodsExcept(['ejecutar', 'controlador'])
           ->getMock();
        $criterio
            ->expects($this->once())
            ->method('ejecutarControlador')
            ->with($controlador);
        $criterio->controlador($controlador);
        $criterio->ejecutar();
    }

    /**
     * @dataProvider dataCorrectoFuncionarDeUnControlador
     * @dataProvider dataNoLlamarAlMetodoIndiceNiPosindiceSiElRegistroErrorEsTrue
     * @dataProvider dataNoLlamarAlMetodoRenderizarSiElRegistroRenderizarEsFalse
     * @dataProvider dataNoLlamarAlMetodoIndiceNiPosindiceSiElRegistroSaltarEsTrueYContinuarTambien
     * @dataProvider dataLlamarAlMetodoErrorSiElRegistroSaltarYErrorSonTrue
     */
    public function testOrdenDeEjecucionSegunElCriterioEsperado(Registros $registros, array $metodosQueDebenSerLlamados, array $metodosQueNoDebenSerLlamados): void
    {
        $ordenDeLlamadas = [];
        $controlador = $this->createMock(Controlador::class);

        $controlador
            ->method('registros')
            ->willReturn($registros);

        foreach( $metodosQueDebenSerLlamados as $metodo ) {
            $controlador
                ->expects($this->once())
                ->method($metodo)
                ->willReturnCallback(function() use (&$ordenDeLlamadas, $metodo) {
                    array_push($ordenDeLlamadas, $metodo);
                });
        }

        foreach( $metodosQueNoDebenSerLlamados as $metodo ) {
            $controlador
                ->expects($this->never())
                ->method($metodo);
        }

        $this->criterio->ejecutarControlador($controlador);
        $this->assertSame($metodosQueDebenSerLlamados, $ordenDeLlamadas);
    }

    public function dataCorrectoFuncionarDeUnControlador(): array
    {
        $registros = new Registros();
        $registros->error = false;
        $registros->saltar = false;
        $registros->continuar = false;
        $registros->renderizar = true;
        return [
            [
                $registros,
                ['iniciar', 'preindice', 'indice', 'posindice', 'renderizar', 'finalizar'],
                ['error']
            ]
        ];
    }

    public function dataNoLlamarAlMetodoIndiceNiPosindiceSiElRegistroErrorEsTrue(): array
    {
        $registros = new Registros();
        $registros->error = true;
        $registros->saltar = false;
        $registros->continuar = false;
        $registros->renderizar = true;
        return [
            [
                $registros,
                ['iniciar', 'preindice', 'error', 'renderizar', 'finalizar'],
                ['indice', 'posindice']
            ]
        ];
    }

    public function dataNoLlamarAlMetodoRenderizarSiElRegistroRenderizarEsFalse(): array
    {
        $registros = new Registros();
        $registros->error = false;
        $registros->saltar = false;
        $registros->continuar = false;
        $registros->renderizar = false;
        return [
            [
                $registros,
                ['iniciar', 'preindice', 'indice', 'posindice', 'finalizar'],
                ['renderizar']
            ]
        ];
    }

    public function dataNoLlamarAlMetodoIndiceNiPosindiceSiElRegistroSaltarEsTrueYContinuarTambien(): array
    {
        $registros = new Registros();
        $registros->error = false;
        $registros->saltar = true;
        $registros->continuar = true;
        $registros->renderizar = true;
        return [
            [
                $registros,
                ['iniciar', 'preindice', 'renderizar', 'finalizar'],
                ['error', 'indice', 'posindice']
            ]
        ];
    }

    public function dataLlamarAlMetodoErrorSiElRegistroSaltarYErrorSonTrue(): array
    {
        $registros = new Registros();
        $registros->error = true;
        $registros->saltar = true;
        $registros->continuar = false;
        $registros->renderizar = true;
        return [
            [
                $registros,
                ['iniciar', 'preindice', 'error', 'renderizar', 'finalizar'],
                ['indice', 'posindice']
            ]
        ];
    }

}
