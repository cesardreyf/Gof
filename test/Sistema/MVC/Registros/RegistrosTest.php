<?php

declare(strict_types=1);

namespace Test\Sistema\MVC\Registros;

use Gof\Sistema\MVC\Registros\Errores;
use Gof\Sistema\MVC\Registros\Registros;
use PHPUnit\Framework\TestCase;

class RegistrosTest extends TestCase
{
    private Registros $registros;

    public function setUp(): void
    {
        $this->registros = new Registros();
    }

    public function testMetodoErroresDevuelveUnaInstanciaDelRegistroDeErrores(): void
    {
        $this->assertInstanceOf(Errores::class, $this->registros->errores());
    }

}
