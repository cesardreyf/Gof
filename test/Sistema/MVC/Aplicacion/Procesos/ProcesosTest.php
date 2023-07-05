<?php

declare(strict_types=1);

namespace Test\Sistema\MVC\Aplicacion\Procesos;

use Gof\Sistema\MVC\Aplicacion\Interfaz\Ejecutable;
use Gof\Sistema\MVC\Aplicacion\Procesos\Prioridad;
use Gof\Sistema\MVC\Aplicacion\Procesos\Procesos;
use PHPUnit\Framework\TestCase;

class ProcesosTest extends TestCase
{

    public function testAgregarYEjecutarProcesosPorPrioridad(): void
    {
        $orden = [];
        $procesos = new Procesos();

        $procesoDePrioridadAlta = $this->createMock(Ejecutable::class);
        $procesoDePrioridadMedia = $this->createMock(Ejecutable::class);
        $procesoDePrioridadBaja = $this->createMock(Ejecutable::class);

        $procesoDePrioridadAlta
            ->expects($this->once())
            ->method('ejecutar')
            ->willReturnCallback(function() use (&$orden) {
                array_push($orden, 0);
            });

        $procesoDePrioridadMedia
            ->expects($this->once())
            ->method('ejecutar')
            ->willReturnCallback(function() use (&$orden) {
                array_push($orden, 1);
            });

        $procesoDePrioridadBaja
            ->expects($this->once())
            ->method('ejecutar')
            ->willReturnCallback(function() use (&$orden) {
                array_push($orden, 2);
            });

        $procesos->agregar($procesoDePrioridadAlta, Prioridad::Alta);
        $procesos->agregar($procesoDePrioridadMedia, Prioridad::Alta);
        $procesos->agregar($procesoDePrioridadBaja, Prioridad::Alta);

        $procesos->ejecutar();
        $ordenDeEjecucionEsperado = [0, 1, 2];
        $this->assertSame($ordenDeEjecucionEsperado, $orden);
    }

}
