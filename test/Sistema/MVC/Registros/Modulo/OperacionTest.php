<?php

declare(strict_types=1);

namespace Test\Sistema\MVC\Registros\Modulo;

use Gof\Interfaz\Lista\Datos;
use Gof\Sistema\MVC\Registros\Modulo\Operacion;
use PHPUnit\Framework\TestCase;

class OperacionTest extends TestCase
{

    public function testMetodoGuardadoDevuelveLoPasadoPorElConstructor(): void
    {
        $listaDeGestoresDeGuardado = $this->createMock(Datos::class);
        $listaDeGestoresDeImpresion = $this->createMock(Datos::class);

        $operacion = new Operacion($listaDeGestoresDeGuardado, $listaDeGestoresDeImpresion);
        $this->assertSame($listaDeGestoresDeGuardado, $operacion->guardado());
        $this->assertSame($listaDeGestoresDeImpresion, $operacion->impresion());
    }

}
