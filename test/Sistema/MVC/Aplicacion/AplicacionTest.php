<?php

declare(strict_types=1);

namespace Test\Sistema\MVC\Aplicacion;

use Gof\Sistema\MVC\Aplicacion\Aplicacion;
use Gof\Sistema\MVC\Aplicacion\Procesos\Prioridad;
use Gof\Sistema\MVC\Aplicacion\Procesos\Procesos;
use Gof\Sistema\MVC\Interfaz\Ejecutable;
use PHPUnit\Framework\TestCase;

class AplicacionTest extends TestCase
{
    private Aplicacion $aplicacion;

    public function setUp(): void
    {
        $this->aplicacion = new Aplicacion();
    }

    public function testMetodoProcesosDevuelveUnaInstanciaDelModuloDeProcesos(): void
    {
        $this->assertInstanceOf(Procesos::class, $this->aplicacion->procesos());
    }

    public function testEjecutarProcesosPorPrioridad(): void
    {
        $procesoPrioridadAlta = $this->createMock(Ejecutable::class);
        $procesoPrioridadMedia = $this->createMock(Ejecutable::class);
        $procesoPrioridadBaja = $this->createMock(Ejecutable::class);

        $this->aplicacion->procesos()->agregar($procesoPrioridadAlta, Prioridad::Alta);
        $this->aplicacion->procesos()->agregar($procesoPrioridadMedia, Prioridad::Media);
        $this->aplicacion->procesos()->agregar($procesoPrioridadBaja, Prioridad::Baja);

        $orden = [];
        $ordenEsperado = [
            Prioridad::Alta,
            Prioridad::Media,
            Prioridad::Baja
        ];

        $procesoPrioridadAlta
            ->expects($this->once())
            ->method('ejecutar')
            ->willReturnCallback(function() use (&$orden) {
                array_push($orden, Prioridad::Alta);
            });

        $procesoPrioridadMedia
            ->expects($this->once())
            ->method('ejecutar')
            ->willReturnCallback(function() use (&$orden) {
                array_push($orden, Prioridad::Media);
            });

        $procesoPrioridadBaja
            ->expects($this->once())
            ->method('ejecutar')
            ->willReturnCallback(function() use (&$orden) {
                array_push($orden, Prioridad::Baja);
            });

        $this->aplicacion->ejecutar();
        $this->assertSame($ordenEsperado, $orden);
    }

    public function testInterrumpirEjecucionDeLosProcesos(): void
    {
        $ordenDeEjecucion = [];
        $cualquierPrioridad = Prioridad::Alta;
        $procesoQueInterrumpeLaEjecucion = 3;
        $ordenDeEjecucionEsperado = [];
        $tope = $procesoQueInterrumpeLaEjecucion + 3;

        for( $indice = 0; $indice < $tope; $indice++) {
            $proceso = $this->createMock(Ejecutable::class);
            $proceso
                ->expects($indice <= $procesoQueInterrumpeLaEjecucion ? $this->once() : $this->never())
                ->method('ejecutar')
                ->willReturnCallback(function() use (&$ordenDeEjecucion, $indice, $procesoQueInterrumpeLaEjecucion) {
                    array_push($ordenDeEjecucion, $indice);

                    if( $procesoQueInterrumpeLaEjecucion == $indice ) {
                        $this->aplicacion->interrumpir();
                    }
                });

            if( $indice <= $procesoQueInterrumpeLaEjecucion ) {
                $ordenDeEjecucionEsperado[] = $indice;
            }

            $this->aplicacion->procesos()->agregar($proceso, $cualquierPrioridad);
        }

        $this->aplicacion->ejecutar();
        $this->assertSame($ordenDeEjecucionEsperado, $ordenDeEjecucion);
    }

}
