<?php

declare(strict_types=1);

namespace Test\Sistema\MVC;

use Gof\Gestor\Autoload\Autoload;
use Gof\Sistema\MVC\Registros\Registros;
use Gof\Sistema\MVC\Sistema;
use PHPUnit\Framework\TestCase;

class SistemaTest extends TestCase
{
    private Sistema $sistema;

    public function setUp(): void
    {
        $this->sistema = new Sistema();
    }

    public function testMetodoRegistrosDevuelveUnaInstanciaDelGestorDeRegistros(): void
    {
        $this->assertInstanceOf(Registros::class, $this->sistema->registros());
    }

    public function testMetodoAutoloadDevuelveUnaInstanciaDelGestorDeAutoload(): void
    {
        $this->assertInstanceOf(Autoload::class, $this->sistema->autoload());
    }

}
