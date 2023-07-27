<?php

declare(strict_types=1);

namespace Test\Sistema\MVC\Rut\Observador;

use Gof\Gestor\Enrutador\Rut\Eventos\Al;
use Gof\Gestor\Enrutador\Rut\Eventos\Interfaz\Evento;
use Gof\Gestor\Enrutador\Rut\Eventos\Interfaz\Observador;
use Gof\Sistema\MVC\Rut\Datos\Ruta;
use Gof\Sistema\MVC\Rut\Observador\Identificador;
use PHPUnit\Framework\TestCase;

class IdentificadorTest extends TestCase
{
    private Identificador $identificador;

    public function setUp(): void
    {
        $this->identificador = new Identificador();
    }

    public function testEsUnObservador(): void
    {
        $this->assertInstanceOf(Observador::class, $this->identificador);
    }

    public function testEmpezarElIdlEnCero(): void
    {
        $this->assertSame(0, $this->identificador->idl());
    }

    public function testAsignarIdALaRuta(): void
    {
        $idLibre = $this->identificador->idl();
        $ruta = $this->createMock(Ruta::class);
        $ruta
            ->expects($this->once())
            ->method('asignarIdentificador')
            ->with($idLibre);
        $this->assertSame(0, $idLibre);
        $this->identificador->asignarId($ruta);
        $this->assertSame($idLibre + 1, $this->identificador->idl());
    }

    public function testDelegarAlMetodoAsignarIdAlRecibirUnEvento(): void
    {
        $ruta = $this->createMock(Ruta::class);
        $evento = $this->createMock(Evento::class);
        $identificador = $this->getMockBuilder(Identificador::class)
            ->onlyMethods(['asignarId'])
            ->getMock();
        $evento
            ->method('tipo')
            ->willReturn(Al::Agregar);
        $evento
            ->method('ruta')
            ->willReturn($ruta);
        $identificador
            ->expects($this->once())
            ->method('asignarId')
            ->with($ruta);
        $identificador->evento($evento);
    }

}
