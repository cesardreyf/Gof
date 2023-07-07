<?php

declare(strict_types=1);

namespace Test\Sistema\MVC\Rutas;

use Gof\Interfaz\Enrutador\Enrutador;
use Gof\Sistema\MVC\Datos\Info;
use Gof\Sistema\MVC\Rutas\Excepcion\EnrutadorInexistente;
use Gof\Sistema\MVC\Rutas\Rutas;
use Gof\Sistema\MVC\Rutas\Simple\Gestor as GestorSimple;
use PHPUnit\Framework\TestCase;

class RutasTest extends TestCase
{
    private Info $info;
    private Rutas $rutas;

    public function setUp(): void
    {
        $this->info = new Info();
        $this->rutas = new Rutas($this->info);
    }

    public function testAlInstanciarLaClaseNoExisteNingunEnrutador(): void
    {
        $this->assertNull($this->rutas->gestor());
    }

    public function testProcesarSinUnEnrutadorLanzaUnaExcepcion(): void
    {
        $this->expectException(EnrutadorInexistente::class);
        $this->rutas->procesar();
    }

    public function testMetodoGestorCambiaElGestorDeRutas(): void
    {
        $primero = $this->createMock(Enrutador::class);
        $segundo = $this->createMock(Enrutador::class);

        $this->assertSame($primero, $this->rutas->gestor($primero));
        $this->assertSame($segundo, $this->rutas->gestor($segundo));

        $this->assertNotSame($primero, $this->rutas->gestor());
    }

    public function testProcesarObtieneInformacionDelEnrutador(): void
    {
        $enrutador = $this->createMock(Enrutador::class);
        $this->rutas->gestor($enrutador);

        $clase = 'Nombre\Completo\De\La\Clase';
        $resto = ['parametro1', 'parametro2'];

        $enrutador
            ->expects($this->once())
            ->method('nombreClase')
            ->willReturn($clase);

        $enrutador
            ->expects($this->once())
            ->method('resto')
            ->willReturn($resto);

        $this->rutas->procesar();
        $this->assertSame($clase, $this->info->controlador);
        $this->assertSame($resto, $this->info->parametros);
    }

    public function testMetodoSimpleDevuelveUnaInstanciaDelGestorSimple(): void
    {
        $this->assertNull($this->rutas->gestor());
        $this->assertInstanceOf(GestorSimple::class, $this->rutas->simple());
    }

}
