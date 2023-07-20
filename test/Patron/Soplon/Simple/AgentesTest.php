<?php

declare(strict_types=1);

namespace Test\Patron\Soplon\Simple;

use Gof\Patron\Soplon\Simple\Agentes;
use Gof\Patron\Soplon\Simple\Datos\ID;
use Gof\Patron\Soplon\Simple\Excepcion\AgenteInexistente;
use Gof\Patron\Soplon\Simple\Interfaz\Agente;
use PHPUnit\Framework\TestCase;

class AgentesTest extends TestCase
{
    private Agentes $agentes;

    public function setUp(): void
    {
        $this->agentes = new Agentes();
    }

    public function testAgregarDevuelveUnIdentificador(): void
    {
        $agente = $this->createMock(Agente::class);
        $identificador = $this->agentes->agregar($agente);
        $this->assertInstanceOf(ID::class, $identificador);
        $this->assertSame(0, $identificador->id());
        $listaEsperada = [$agente];
        $this->assertSame($listaEsperada, $this->agentes->lista());
    }

    public function testRemoverQuitaElAgenteDeLaLista(): void
    {
        $agente = $this->createMock(Agente::class);
        $agenteAgregado = $this->agentes->agregar($agente);
        $this->assertNotEmpty($this->agentes->lista());
        $this->assertTrue($this->agentes->remover($agenteAgregado));
        $this->assertEmpty($this->agentes->lista());
    }

    public function testLanzarExcepcionAlIntentarRemoverUnAgenteInexistente(): void
    {
        $identificadorFicticio = $this->createMock(ID::class);
        $identificadorFicticio
            ->expects($this->once())
            ->method('id')
            ->willReturn(256);
        $this->assertFalse($this->agentes->remover($identificadorFicticio));
    }

    public function testCambiarUnAgenteAgregadoPorOtroNuevo(): void
    {
        $agenteViejo = $this->createMock(Agente::class);
        $agenteNuevo = $this->createMock(Agente::class);
        $idDelAgenteViejo = $this->agentes->agregar($agenteViejo);
        $this->assertCount(1, $this->agentes->lista());
        $this->assertTrue($this->agentes->cambiar($idDelAgenteViejo, $agenteNuevo));
        $this->assertSame($agenteNuevo, $this->agentes->obtener($idDelAgenteViejo));
        $this->assertCount(1, $this->agentes->lista());
    }

    public function testCambiarDevuelveFalseSiNoExisteElAgenteViejo(): void
    {
        $idDeUnAgenteViejoInexistente = $this->createMock(ID::class);
        $idDeUnAgenteViejoInexistente
            ->method('id')
            ->willReturn(0);
        $this->assertFalse($this->agentes->cambiar($idDeUnAgenteViejoInexistente, $this->createMock(Agente::class)));
    }

    public function testObtenerUnAgenteSegunSuIdentificador(): void
    {
        $agente = $this->createMock(Agente::class);
        $identificador = $this->agentes->agregar($agente);
        $this->assertSame($agente, $this->agentes->obtener($identificador));
    }

    public function testLanzarExcepcionAlObtenerUnAgenteInexistente(): void
    {
        $this->expectException(AgenteInexistente::class);
        $identificadorFicticio = $this->createMock(ID::class);
        $identificadorFicticio
            ->method('id')
            ->willReturn(1024);
        $this->agentes->obtener($identificadorFicticio);
    }

}
