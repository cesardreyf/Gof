<?php

declare(strict_types=1);

namespace Test\Sistema\MVC\Registros\Errores;

use Gof\Sistema\MVC\Registros\Errores\Traductor;
use Gof\Sistema\MVC\Registros\Interfaz\Error;
use Gof\Sistema\MVC\Registros\Interfaz\ErrorTraducible;
use PHPUnit\Framework\TestCase;

class TraductorTest extends TestCase
{
    private Traductor $traductor;

    public function setUp(): void
    {
        $this->traductor = new Traductor();
    }

    public function testInstanciaDeErrorTraducible(): void
    {
        $this->assertInstanceOf(ErrorTraducible::class, $this->traductor);
    }

    // public function testTraducirError(Error $error, string $mensajeEsperado): void
    // {
    //     $this->assertSame($mensajeEsperado, $this->traductor($error));
    // }

    // public function dataErroresYMensajesEsperados(): array
    // {
    //     $error = $this->createMock(
    //     return [
    //         [$error, $mensajeEsperado]
    //     ];
    // }

}
