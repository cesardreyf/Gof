<?php

declare(strict_types=1);

namespace Test\Sistema\MVC\Controlador\Criterio;

use Closure;
use Gof\Sistema\MVC\Aplicacion\DAP\DAP;
use Gof\Sistema\MVC\Controlador\Criterio\Ipiperf;
use Gof\Sistema\MVC\Controlador\Criterio\Ipiperf\Datos\Registros;
use Gof\Sistema\MVC\Controlador\Criterio\Ipiperf\Interfaz\Controlador as ControladorDelCriterio;
use Gof\Sistema\MVC\Controlador\Excepcion\ControladorIndefinido;
use Gof\Sistema\MVC\Controlador\Excepcion\ControladorInvalido;
use Gof\Sistema\MVC\Controlador\Interfaz\Controlador as ControladorBase;
use PHPUnit\Framework\TestCase;

class IpiperfTest extends TestCase
{
    private DAP $dap;

    public function setUp(): void
    {
        $this->dap = $this->createMock(DAP::class);
    }

    public function testLanzarExcepcionSiSeEjecutaSinDefinirElControlador(): void
    {
        $this->expectException(ControladorIndefinido::class);
        $criterio = new Ipiperf();
        $criterio->ejecutar($this->dap);
    }

    public function testLanzarExcepcionSiElControladorNoImplementaLaInterfazDelCriterio(): void
    {
        $this->expectException(ControladorInvalido::class);
        $criterio = new Ipiperf();
        $criterio->controlador($this->createMock(ControladorBase::class));
        $criterio->ejecutar($this->dap);
    }

    /**
     * @dataProvider dataCorrectoFuncionarDeLaEjecucion
     * @dataProvider dataNoRenderizar
     * @dataProvider dataNoEjecutaIndiceNiPosindiceSiPreindiceMarcaError
     * @dataProvider dataEjecutarIndiceSiHayErrorPeroElRegistroContinuarTambienEstaActivo
     * @dataProvider dataLlamarAErrorPeroContinuarConElOrdenEsperado
     * @dataProvider dataNoEjecutarPreindiceNiIndiceNiPosindiceSiElRegistroSaltarEstaActivo
     */
    public function testEjecucionDelControlador(Registros $registros, array $metodosInesperados, array $ordenEsperado, ?array $cambios = null): void
    {
        $orden = [];
        $criterio = new Ipiperf();
        $veces = array_count_values($ordenEsperado);
        $controlador = $this->createMock(ControladorDelCriterio::class);
        $controlador
            ->method('registros')
            ->willReturnCallback(
                function() use (&$registros) {
                    return $registros;
                }
            );
        foreach( array_unique($ordenEsperado) as $metodo ) {
            $controlador
                ->expects($this->exactly($veces[$metodo]))
                ->method($metodo)
                ->willReturnCallBack(
                    Closure::bind(function() use (&$orden, $metodo, $cambios) {
                        array_push($orden, $metodo);
                        if( isset($cambios[$metodo]) ) {
                            foreach( $cambios[$metodo] as $propiedad => $valor ) {
                                $this->registros()->{$propiedad} = $valor;
                            }
                        }
                    }, $controlador, ControladorDelCriterio::class)
                );
        }
        foreach( $metodosInesperados as $metodo ) {
            $controlador
                ->expects($this->never())
                ->method($metodo);
        }
        $criterio->controlador($controlador);
        $criterio->ejecutar($this->dap);
        $this->assertSame($ordenEsperado, $orden);
    }

    public function dataCorrectoFuncionarDeLaEjecucion(): array
    {
        $registros = new Registros();
        $registros->error = false;
        $registros->continuar = false;
        $registros->saltar = false;
        $registros->renerizar = true;
        return [[
            $registros,
            ['error'],
            ['iniciar', 'preindice', 'indice', 'posindice', 'renderizar', 'finalizar']
        ]];
    }

    public function dataNoRenderizar(): array
    {
        $registros = new Registros();
        $registros->renderizar = false;
        return [[
            $registros,
            ['renderizar'],
            ['iniciar', 'preindice', 'indice', 'posindice', 'finalizar'],
        ]];
    }

    public function dataNoEjecutaIndiceNiPosindiceSiPreindiceMarcaError(): array
    {
        $registros = new Registros();
        return [[
            $registros,
            ['indice', 'posindice'],
            ['iniciar', 'preindice', 'error', 'renderizar', 'finalizar'],
            [
                'preindice' => [
                    'error' => true
                ]
            ]
        ]];
    }

    public function dataEjecutarIndiceSiHayErrorPeroElRegistroContinuarTambienEstaActivo(): array
    {
        $registros = new Registros();
        return [[
            $registros,
            [],
            ['iniciar', 'preindice', 'error', 'indice', 'posindice', 'renderizar', 'finalizar'],
            [
                'preindice' => [
                    'error' => true,
                    'continuar' => true,
                ]
            ]
        ]];
    }

    public function dataLlamarAErrorPeroContinuarConElOrdenEsperado(): array
    {
        $registros = new Registros();
        return [[
            $registros,
            [],
            ['iniciar', 'preindice', 'error', 'indice', 'error', 'posindice', 'renderizar', 'finalizar'],
            [
                'preindice' => [
                    'error' => true,
                    'continuar' => true,
                ],
                'indice' => [
                    'error' => true,
                    'continuar' => true,
                ],
            ]
        ]];
    }

    public function dataNoEjecutarPreindiceNiIndiceNiPosindiceSiElRegistroSaltarEstaActivo(): array
    {
        $registros = new Registros();
        $registros->saltar = true;
        $registros->renderizar = true;
        return [[
            $registros,
            ['preindice', 'indice', 'posindice'],
            ['iniciar', 'renderizar', 'finalizar'],
            []
        ]];
    }

}
