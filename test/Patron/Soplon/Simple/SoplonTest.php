<?php

declare(strict_types=1);

namespace Test\Patron\Soplon\Simple;

use Gof\Patron\Soplon\Simple\Agente;
use Gof\Patron\Soplon\Simple\Agentes;
use Gof\Patron\Soplon\Simple\Soplon;
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
        $soplon->agentes()->agregar($agente);
        $agente
            ->expects($this->once())
            ->method('aviso');
        $soplon->avisar();
    }

}
