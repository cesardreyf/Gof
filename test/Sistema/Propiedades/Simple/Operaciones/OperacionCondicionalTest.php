<?php

declare(strict_types=1);

namespace Tst\Sistema\Propiedades\Simple\Operaciones;

use Gof\Gestor\Acciones\Interfaz\Accion;
use Gof\Gestor\Propiedades\Propiedad;
use Gof\Sistema\Propiedades\Simple\Interfaz\Condicion;
use Gof\Sistema\Propiedades\Simple\Operaciones\OperacionCondicional;
use Gof\Sistema\Propiedades\Simple\Operaciones\OperacionSimple;
use PHPUnit\Framework\TestCase;

class OperacionCondicionalTest extends TestCase
{
    private Accion $accion;
    private Condicion $condicion;
    private Propiedad $propiedades;
    private OperacionCondicional $operacion;

    public function setUp(): void
    {
        $this->accion = $this->createMock(Accion::class);
        $this->condicion = $this->createMock(Condicion::class);
        $this->propiedades = $this->createMock(Propiedad::class);
        $this->operacion = new OperacionCondicional($this->propiedades, $this->accion, $this->condicion);
    }

    public function testExtenderDeLaClaseOperacionSimple(): void
    {
        $this->assertInstanceOf(OperacionSimple::class, $this->operacion);
    }

    /**
     * @dataProvider dataCondicionesYResultadosEsperados
     */
    public function testOperarSegunLaCondicion(bool $condicion, int $retornoDelAccionar, bool $resultadoEsperadoDelAccionar): void
    {
        $this->propiedades
             ->method('lista')
             ->willReturn(['algo']);

        $this->condicion
             ->expects($this->once())
             ->method('condicion')
             ->willReturn($condicion);

        $this->accion
             ->method('accionar')
             ->willReturn($retornoDelAccionar);

        $this->assertSame($resultadoEsperadoDelAccionar, $this->operacion->operar());
    }

    public function dataCondicionesYResultadosEsperados(): array
    {
        return [
            [true,  0, true],
            [false, 0, false],
            [true,  1, false],
            [false, 1, false]
        ];
    }

    public function testOperarSiLaCondicionEsFalsaDevuelveUnError(): void
    {
        $this->condicion
             ->expects($this->once())
             ->method('condicion')
             ->willReturn(false);

        $this->accion
             ->expects($this->never())
             ->method('accionar');

        $this->assertFalse($this->operacion->operar());
        $this->assertTrue($this->operacion->errores()->hay());
        
        $identificadorDelError = OperacionCondicional::IDENTIFICADOR_RESERVADO;
        $errorEsperado = OperacionCondicional::CONDICION_INCUMPLIDA;

        $listaDeErrorEsperado = [$identificadorDelError => $errorEsperado];
        $this->assertSame($listaDeErrorEsperado, $this->operacion->errores()->lista());
    }

    public function testMetodoSegunCambiaLaCondicionDelOperador(): void
    {
        $this->condicion->method('condicion')->willReturn(false);
        $this->assertSame($this->condicion, $this->operacion->segun());
        $this->assertFalse($this->operacion->segun()->condicion());

        $nuevaCondicion = $this->createMock(Condicion::class);
        $nuevaCondicion->method('condicion')->willReturn(true);

        $this->assertSame($nuevaCondicion, $this->operacion->segun($nuevaCondicion));
        $this->assertNotSame($this->condicion, $this->operacion->segun());
        $this->assertTrue($this->operacion->segun()->condicion());
    }

}
