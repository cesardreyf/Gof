<?php

declare(strict_types=1);

namespace Test\Sistema\MVC\Rut;

use Gof\Gestor\Enrutador\Rut\EnrutadorConEventos;
use Gof\Sistema\MVC\Rut\Rut;
use Gof\Sistema\MVC\Rut\ListaDeObservadores;
use PHPUnit\Framework\TestCase;

class RutTest extends TestCase
{
    private Rut $rut;

    public function setUp(): void
    {
        $this->rut = new Rut();
    }

    public function testExtenderDeEnrutadorConEventos(): void
    {
        $this->assertInstanceOf(EnrutadorConEventos::class, $this->rut);
    }

    public function testIncluirObservadoresPorDefecto(): void
    {
        $listaDeObservadoresEsperado = new ListaDeObservadores();
        $observadoresPorDefecto = $this->rut->eventos()->observadores()->lista();
        $this->assertEquals($listaDeObservadoresEsperado->lista(), $observadoresPorDefecto);
    }

}
