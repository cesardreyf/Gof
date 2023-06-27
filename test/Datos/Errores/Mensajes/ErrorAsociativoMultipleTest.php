<?php

declare(strict_types=1);

namespace Test\Datos\Errores\Mensajes;

use Gof\Datos\Errores\Mensajes\ErrorAsociativoMultiple;
use Gof\Interfaz\Errores\Mensajes\Error as IError;
use Gof\Interfaz\Errores\Mensajes\ErrorAsociativo as IErrorAsociativo;
use PHPUnit\Framework\TestCase;

class ErrorAsociativoMultipleTest extends TestCase
{
    private ErrorAsociativoMultiple $error;

    public function setUp(): void
    {
        $this->error = new ErrorAsociativoMultiple();
    }

    public function testImplementaError(): void
    {
        $this->assertInstanceOf(IError::class, $this->error);
        $this->assertInstanceOf(IErrorAsociativo::class, $this->error);
    }

    public function testListaDeErroresLimpiosAlInstanciar(): void
    {
        $this->assertEmpty($this->error->lista());
        $this->assertFalse($this->error->hay());
    }

    public function testAgregarErroresConClavesYObtenerLaListaEsperada(): ErrorAsociativoMultiple
    {
        $fila0 = 'fila_0';
        $fila0_columna0 = 'columna_0';
        $fila0_mensaje = 'Campo vacío';
        $fila0_codigo = 101010;

        $fila1 = 'fila_1';
        $fila1_columna0 = 'columna_0';
        $fila1_mensaje = 'Campo inexistente';
        $fila1_codigo = 202020;

        $this->error->clave($fila0, $fila0_columna0);
        $this->error->mensaje($fila0_mensaje);
        $this->error->codigo($fila0_codigo);

        $this->error->clave($fila1, $fila1_columna0);
        $this->error->mensaje($fila1_mensaje);
        $this->error->codigo($fila1_codigo);

        $listaDeErrorEsperado = [
            $fila0 => [
                $fila0_columna0 => [
                    ErrorAsociativoMultiple::CODIGO => $fila0_codigo,
                    ErrorAsociativoMultiple::MENSAJE => $fila0_mensaje
                ]
            ],
            $fila1 => [
                $fila1_columna0 => [
                    ErrorAsociativoMultiple::CODIGO => $fila1_codigo,
                    ErrorAsociativoMultiple::MENSAJE => $fila1_mensaje
                ]
            ],
        ];

        $this->assertEquals($listaDeErrorEsperado, $this->error->lista());
        return $this->error;
    }

    /**
     * @depends testAgregarErroresConClavesYObtenerLaListaEsperada
     */
    public function testLimpiarErrores(ErrorAsociativoMultiple $error): void
    {
        $this->assertTrue($error->hay());
        $this->assertNotEmpty($error->lista());

        $error->limpiar();

        $this->assertFalse($error->hay());
        $this->assertEmpty($error->lista());
    }

    public function testAgregarCodigoYMensajeDeErrorSinEspecificarClave(): void
    {
        $codigo = 404;
        $mensaje = 'pagina_inexistente';

        $this->error->codigo($codigo);
        $this->error->mensaje($mensaje);

        $this->assertSame($mensaje, $this->error->mensaje());
        $this->assertSame($codigo, $this->error->codigo());
    }

}
