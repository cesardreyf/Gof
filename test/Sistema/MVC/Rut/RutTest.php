<?php

declare(strict_types=1);

namespace Test\Sistema\MVC\Rut;

use Gof\Gestor\Enrutador\Rut\EnrutadorConEventos;
use Gof\Sistema\MVC\Rut\ListaDeObservadores;
use Gof\Sistema\MVC\Rut\Rut;
use Gof\Sistema\MVC\Sistema;
use PHPUnit\Framework\TestCase;

class RutTest extends TestCase
{
    private Rut $rut;
    private Sistema $sistemaMVC;

    public function setUp(): void
    {
        $this->sistemaMVC = $this->createMock(Sistema::class);
        $this->rut = new Rut($this->sistemaMVC);
    }

    public function testExtenderDeEnrutadorConEventos(): void
    {
        $this->assertInstanceOf(EnrutadorConEventos::class, $this->rut);
    }

    public function testIncluirObservadoresPorDefecto(): void
    {
        $listaDeObservadoresEsperado = new ListaDeObservadores($this->sistemaMVC);
        $observadoresPorDefecto = $this->rut->eventos()->observadores()->lista();
        $this->assertEquals($listaDeObservadoresEsperado->lista(), $observadoresPorDefecto);
    }

}
