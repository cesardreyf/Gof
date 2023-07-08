<?php

declare(strict_types=1);

namespace Test\Sistema\MVC\Aplicacion\Procesos;

use Gof\Sistema\MVC\Aplicacion\Excepcion\PermisosInsuficientes;
use Gof\Sistema\MVC\Aplicacion\Excepcion\PrioridadIlegal;
use Gof\Sistema\MVC\Aplicacion\Procesos\Prioridad;
use Gof\Sistema\MVC\Aplicacion\Procesos\Procesos;
use Gof\Sistema\MVC\Interfaz\Ejecutable;
use PHPUnit\Framework\TestCase;

class ProcesosTest extends TestCase
{

    /**
     * @dataProvider dataPrioridades
     */
    public function testAgregaEjecutablesConPrioridadUnica(Prioridad $prioridad): void
    {
        $listaDeProcesos         = [];
        $procesos                = new Procesos($listaDeProcesos, $prioridad);
        $ejecutable              = $this->createMock(Ejecutable::class);
        $listaDeProcesosEsperado = [
            $prioridad->value => [$ejecutable]
        ];

        $procesos->agregar($ejecutable, $prioridad);
        $this->assertSame($listaDeProcesos, $procesos->lista());
        $this->assertSame($listaDeProcesosEsperado, $listaDeProcesos);
    }

    public function dataPrioridades(): array
    {
        return [Prioridad::cases()];
    }

    /**
     * @dataProvider dataPrioridadPermitidaYNoPermitida
     */
    public function testLanzarExcepcionSiAgregaUnEjecutableConPrioridadSinPermisos(Prioridad $prioridadPermitida, Prioridad $prioridadNoPermitida): void
    {
        $this->expectException(PrioridadIlegal::class);
        $listaDeProcesos = [];
        $procesos = new Procesos($listaDeProcesos, $prioridadPermitida);
        $procesos->agregar($this->createMock(Ejecutable::class), $prioridadNoPermitida);
    }

    public function dataPrioridadPermitidaYNoPermitida(): array
    {
        foreach( Prioridad::cases() as $prioridadPermitida ) {
            foreach( Prioridad::cases() as $prioridadNoPermitida ) {
                if( $prioridadPermitida !== $prioridadNoPermitida ) {
                    $datos[] = [$prioridadPermitida, $prioridadNoPermitida];
                }
            }
        }
        return [...$datos];
    }

    public function testLanzarExcepcionAlCrearModulosDeProcesosAgregablesSinPermisos(): void
    {
        $this->expectException(PermisosInsuficientes::class);

        $listaDeProcesos = [];
        $procesos = new Procesos($listaDeProcesos, Prioridad::Baja);
        $procesos->agregable(Prioridad::Alta);
    }

    public function testCrearModuloDeProcesosAgregablesConPrioridades(): void
    {
        $listaDeProcesos = [];
        $procesos = new Procesos($listaDeProcesos, Prioridad::Alta, Prioridad::Media, Prioridad::Baja);
        $procesosQueSoloPuedenAsignarPrioridadMedia = $procesos->agregable(Prioridad::Media);
        $this->assertInstanceOf(Procesos::class, $procesosQueSoloPuedenAsignarPrioridadMedia);
        // Continuar
    }

    // Continuará... (algún día)
}
