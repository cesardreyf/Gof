<?php

declare(strict_types=1);

namespace Test\Sistema\Formulario\Mediador\Campo;

use Gof\Sistema\Formulario\Datos\Campo\Error as ErrorDeCampo;
use Gof\Sistema\Formulario\Interfaz\Campo;
use Gof\Sistema\Formulario\Mediador\Campo\Error;
use PHPUnit\Framework\TestCase;

class ErrorTest extends TestCase
{

    public function testEscribirCodigoYMensajeDeErrorEnElCampoAlReportar(): void
    {
        $campo = $this->createMock(Campo::class);
        $error = $this->createMock(ErrorDeCampo::class);

        $codigoDeError = PHP_INT_MAX;
        $mensajeDeError = 'algo';

        $error
            ->expects($this->once())
            ->method('codigo')
            ->with($codigoDeError);

        $error
            ->expects($this->once())
            ->method('mensaje')
            ->with($mensajeDeError);

        $campo
            ->method('error')
            ->willReturn($error);

        Error::reportar($campo, $mensajeDeError, $codigoDeError);
    }

}
