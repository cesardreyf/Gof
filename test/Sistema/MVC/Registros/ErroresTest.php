<?php

declare(strict_types=1);

namespace Test\Sistema\MVC\Registros;

use Gof\Interfaz\Lista\Datos;
use Gof\Sistema\MVC\Registros\Datos\Error;
use Gof\Sistema\MVC\Registros\Errores;
use Gof\Sistema\MVC\Registros\Interfaz\ErrorGuardable;
use Gof\Sistema\MVC\Registros\Interfaz\ErrorImprimible;
use Gof\Sistema\MVC\Registros\Modulo\Operacion;
use PHPUnit\Framework\TestCase;

class ErroresTest extends TestCase
{
    private Errores $errores;

    public function setUp(): void
    {
        $this->errores = new Errores();
    }

    public function testExtiendeDelModuloOperacion(): void
    {
        $this->assertInstanceOf(Operacion::class, $this->errores);
    }

    public function testRegistrarRecorreLaListaDeGestoresDeGuardadoEImpresionYLesPasaElError(): void
    {
        // Caótico, pero funciona...
        $registroDeErrores = $this->getMockBuilder(Errores::class)
            ->setMethodsExcept(['registrar'])
            ->disableOriginalConstructor()
            ->getMock();

        $ultimoError = [
            'type' => 1,
            'message' => 'Mensaje de error',
            'file' => 'Nombre.del.archivo',
            'line' => 666
        ];

        $registroDeErrores
            ->method('obtenerUltimoError')
            ->willReturn($ultimoError);

        $datoError = new Error(
            $ultimoError['type'],
            $ultimoError['message'],
            $ultimoError['file'],
            $ultimoError['line']
        );

        $gestorDeGuardado = $this->createMock(ErrorGuardable::class);
        $gestorDeGuardado
            ->expects($this->once())
            ->method('guardar')
            ->with($datoError);

        $gestorDeImpresion = $this->createMock(ErrorImprimible::class);
        $gestorDeImpresion
            ->expects($this->once())
            ->method('imprimir')
            ->with($datoError);

        $gestoresDeGuardado = $this->createMock(Datos::class);
        $gestoresDeGuardado
            ->expects($this->once())
            ->method('lista')
            ->willReturn(
                [$gestorDeGuardado]
            );

        $gestoresDeImpresion = $this->createMock(Datos::class);
        $gestoresDeImpresion
            ->expects($this->once())
            ->method('lista')
            ->willReturn(
                [$gestorDeImpresion]
            );

        $registroDeErrores
            ->method('guardado')
            ->willReturn($gestoresDeGuardado);

        $registroDeErrores
            ->method('impresion')
            ->willReturn($gestoresDeImpresion);

        $registroDeErrores->guardar = true;
        $registroDeErrores->imprimir = true;
        $registroDeErrores->registrar();
    }

    public function testRegistrarIgnoraElGuardadoSiElUltimoErrorEstaVacio(): void
    {
        $registroDeErrores = $this->getMockBuilder(Errores::class)
            ->setMethodsExcept(['registrar'])
            ->disableOriginalConstructor()
            ->getMock();

        $ultimoError = [];

        $registroDeErrores
            ->method('obtenerUltimoError')
            ->willReturn($ultimoError);

        $gestorDeGuardado = $this->createMock(ErrorGuardable::class);
        $gestorDeGuardado
            ->expects($this->never())
            ->method('guardar');

        $gestoresDeGuardado = $this->createMock(Datos::class);
        $gestoresDeGuardado
            ->expects($this->never())
            ->method('lista');

        $registroDeErrores
            ->method('guardado')
            ->willReturn($gestoresDeGuardado);

        $registroDeErrores->registrar();
    }

}
