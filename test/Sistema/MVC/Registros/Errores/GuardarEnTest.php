<?php

declare(strict_types=1);

namespace Test\Sistema\MVC\Registros\Errores;

use Gof\Contrato\Registro\Registro;
use Gof\Sistema\MVC\Registros\Errores\GuardarEn;
use Gof\Sistema\MVC\Registros\Interfaz\Error;
use Gof\Sistema\MVC\Registros\Interfaz\ErrorGuardable;
use Gof\Sistema\MVC\Registros\Interfaz\ErrorTraducible;
use PHPUnit\Framework\TestCase;

class GuardarEnTest extends TestCase
{
    private Registro $registro;
    private GuardarEn $gGuardado;

    public function setUp(): void
    {
        $this->registro = $this->createMock(Registro::class);
        $this->traductor = $this->createMock(ErrorTraducible::class);
        $this->gGuardado = new GuardarEn($this->registro, $this->traductor);
    }

    public function testImplementaErrorGuardable(): void
    {
        $this->assertInstanceOf(ErrorGuardable::class, $this->gGuardado);
    }

    public function testGuardarError(): void
    {
        $error = $this->createMock(Error::class);
        $traduccion = 'traduccion_del_error';

        $this->traductor
            ->expects($this->once())
            ->method('traducir')
            ->with($error)
            ->willReturn($traduccion);

        $this->registro
            ->expects($this->once())
            ->method('registrar')
            ->with($traduccion);

        $this->registro
            ->expects($this->once())
            ->method('volcar');

        $this->gGuardado->guardar($error);
    }

}
