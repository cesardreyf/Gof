<?php

declare(strict_types=1);

namespace Test\Patron\Soplon\Variadico;

use Gof\Patron\Soplon\Variadico\Agente;
use Gof\Patron\Soplon\Variadico\Agentes;
use Gof\Patron\Soplon\Variadico\Soplon;
use PHPUnit\Framework\TestCase;

class SoplonTest extends TestCase
{

    public function testMetodoAgentesDevuelveUnaInstanciaDelGestorDeAgentes(): void
    {
        $soplon = new Soplon();
        $this->assertInstanceOf(Agentes::class, $soplon->agentes());
    }

    public function testAvisarRecorreListaDeAgentesYLlamaAlMetodoAvisar(): void
    {
        $soplon = new Soplon();
        $agente = $this->createMock(Agente::class);
        $informe = ['parametro1', 'parametro2'];
        $soplon->agentes()->agregar($agente);
        $agente
            ->expects($this->once())
            ->method('avisar')
            ->with(...$informe);
        $soplon->avisar(...$informe);
    }

}
