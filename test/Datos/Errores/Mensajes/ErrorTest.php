<?php

declare(strict_types=1);

namespace Test\Datos\Errores\Mensajes;

use Gof\Datos\Errores\Mensajes\Error;
use Gof\Interfaz\Errores\Mensajes\Error as ErrorInterfaz;
use PHPUnit\Framework\TestCase;

class ErrorTest extends TestCase
{

    public function testImplementaInterfazCorrectamente(): void
    {
        $error = new Error();
        $this->assertInstanceOf(ErrorInterfaz::class, $error);
    }

    public function testDefinirCodigoDeError(): void
    {
        $codigoDeError = 12345;
        $error = new Error($codigoDeError);
        $this->assertSame($codigoDeError, $error->codigo());

        $nuevoCodigoDeError = 67890;
        $error->codigo($nuevoCodigoDeError);
        $this->assertSame($nuevoCodigoDeError, $error->codigo());
    }

    public function testDefinirErroresPrevios(): void
    {
        $erroresPrevios = ['algo', 'bobo', 'como', 'dado', 'este'];
        $error = new Error(0, $erroresPrevios);

        $this->assertSame($erroresPrevios, $error->lista());

        $otroError = 'faso';
        $listaDeErroresEsperado = $erroresPrevios;
        $listaDeErroresEsperado[] = $otroError;

        $error->mensaje($otroError);
        $this->assertNotSame($erroresPrevios, $error->lista());
        $this->assertSame($listaDeErroresEsperado, $error->lista());
    }

    public function testHayErrores(): void
    {
        $error = new Error();
        $this->assertFalse($error->hay());
        $this->assertEmpty($error->lista());

        $error->mensaje('ocurrió un error');
        $this->assertTrue($error->hay());
        $this->assertNotEmpty($error->lista());

        $mensajeDeErrorQuitadoDeLaPila = $error->mensaje();
        $this->assertFalse($error->hay());
        $this->assertEmpty($error->lista());

        $error->codigo(1234567890);
        $this->assertTrue($error->hay());
        $this->assertEmpty($error->lista());

        $error->codigo(0);
        $this->assertFalse($error->hay());
    }

    public function testAgregarMensajeDeError(): void
    {
        $error = new Error();
        $this->assertEmpty($error->mensaje());

        $nuevoMensajeDeError = 'ocurrió un error';
        $error->mensaje($nuevoMensajeDeError);

        $this->assertNotEmpty($error->lista());
        $this->assertSame($nuevoMensajeDeError, $error->mensaje());
        $this->assertEmpty($error->lista());
    }

    /**
     * @dataProvider dataVariosMensajesDeError
     */
    public function testAgregarVariosMensajesDeErrorYObtenerlosDelUltimoAlPrimero(array $variosMensajes): void
    {
        $error = new Error();
        foreach( $variosMensajes as $mensaje ) {
            $this->assertSame($mensaje, $error->mensaje($mensaje));
        }

        $this->assertSame($variosMensajes, $error->lista());
        while( $mensaje = array_pop($variosMensajes) ) {
            $this->assertSame($mensaje, $error->mensaje());
        }

        $this->assertEmpty($error->lista());
    }

    public function dataVariosMensajesDeError(): array
    {
        return [
            [[
                'un mensaje de error',
                'otro mensaje de error',
                'último mensaje de error que ya me da pereza',
            ]]
        ];
    }

    public function testLimpiarErrores(): void
    {
        $error = new Error();
        $mensajeDeError = 'algo';
        $codigoDeError = 1234567890;

        $error->codigo($codigoDeError);
        $error->mensaje($mensajeDeError);

        $this->assertNotEmpty($error->lista());
        $this->assertSame($codigoDeError, $error->codigo());

        $error->limpiar();
        $this->assertEmpty($error->lista());
        $this->assertSame(0, $error->codigo());
    }

    public function testLimpiarErroresPrevios(): void
    {
        $codigoDeError = 1234567890;
        $erroresPrevios = ['un error', 'otro error'];
        $error = new Error($codigoDeError, $erroresPrevios);

        $this->assertSame($codigoDeError, $error->codigo());
        $this->assertSame($erroresPrevios, $error->lista());

        $error->limpiar();
        $this->assertEmpty($error->lista());
        $this->assertSame(0, $error->codigo());
    }

}
