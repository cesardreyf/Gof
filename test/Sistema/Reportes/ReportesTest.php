<?php

declare(strict_types=1);

use Gof\Interfaz\Mensajes\Guardable;
use Gof\Sistema\Reportes\Interfaz\Reportero;
use Gof\Sistema\Reportes\Reportes;
use PHPUnit\Framework\TestCase;

class ReportesTest extends TestCase
{

    public function testInstancias(): void
    {
        $gestorDeGuardadoDeExcepciones = $this->getMockBuilder(Guardable::class)->getMock();
        $gestorDeGuardadoDeErrores = $this->getMockBuilder(Guardable::class)->getMock();
        $sistemaDeReportes = new Reportes($gestorDeGuardadoDeExcepciones, $gestorDeGuardadoDeExcepciones);

        $this->assertInstanceOf(Reportero::class, $sistemaDeReportes->excepciones());
        $this->assertInstanceOf(Reportero::class, $sistemaDeReportes->errores());
    }

}
