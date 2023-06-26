<?php

declare(strict_types=1);

namespace Test\Datos\Errores\Mensajes;

use Gof\Datos\Errores\Mensajes\ErrorAsociativo;
use Gof\Interfaz\Errores\Mensajes\Error as IError;
use Gof\Interfaz\Errores\Mensajes\ErrorAsociativo as IErrorAsociativo;
use PHPUnit\Framework\TestCase;

class ErrorAsociativoTest extends TestCase
{
    private ErrorAsociativo $error;

    public function setUp(): void
    {
        $this->error = new ErrorAsociativo();
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

    /**
     * @dataProvider dataClavesCodigosYMensajesDeErrores
     */
    public function testEscribirUnErrorAsociadoAUnaClave(string $clave, int $codigo, string $mensaje): void
    {
        $this->assertFalse($this->error->clave($clave));
        $this->assertSame($codigo, $this->error->codigo($codigo));
        $this->assertSame($mensaje, $this->error->mensaje($mensaje));;

        $this->assertTrue($this->error->clave($clave));
        $this->assertSame($codigo, $this->error->codigo());
        $this->assertSame($mensaje, $this->error->mensaje());

        $listaDeErrorEsperada = [
            $clave => [
                ErrorAsociativo::CODIGO => $codigo,
                ErrorAsociativo::MENSAJE => $mensaje
            ]
        ];

        $this->assertSame($listaDeErrorEsperada, $this->error->lista());
    }

    public function dataClavesCodigosYMensajesDeErrores(): array
    {
        return [
            ['pagina', 404, 'Página inexistente'],
        ];
    }

    /**
     * @dataProvider dataVariosErrores
     */
    public function testAgregarVariosErroresYObtenerlos(array $errores): void
    {
        foreach( $errores as $clave => $error ) {
            $this->assertFalse($this->error->clave($clave));
            $this->assertSame($error[ErrorAsociativo::CODIGO], $this->error->codigo($error[ErrorAsociativo::CODIGO]));
            $this->assertSame($error[ErrorAsociativo::MENSAJE], $this->error->mensaje($error[ErrorAsociativo::MENSAJE]));
        }

        foreach( $errores as $clave => $error ) {
            $this->assertTrue($this->error->clave($clave));
            $this->assertSame($error[ErrorAsociativo::CODIGO], $this->error->codigo());
            $this->assertSame($error[ErrorAsociativo::MENSAJE], $this->error->mensaje());
        }

        $this->assertSame($errores, $this->error->lista());
    }

    /**
     * @dataProvider dataVariosErrores
     */
    public function testAgregarVariosErroresSinCambiarLaClaveReemplazaLosCodigosYMensajesDeErrores(array $errores): void
    {
        $clave = 'unica';
        foreach( $errores as $error ) {
            $this->error->clave($clave);
            $this->assertSame($error[ErrorAsociativo::CODIGO], $this->error->codigo($error[ErrorAsociativo::CODIGO]));
            $this->assertSame($error[ErrorAsociativo::MENSAJE], $this->error->mensaje($error[ErrorAsociativo::MENSAJE]));
        }

        $listaDeErrorEsperada = [
            $clave => end($errores)
        ];

        $this->assertCount(1, $this->error->lista());
        $this->assertSame($listaDeErrorEsperada, $this->error->lista());
    }

    /**
     * @dataProvider dataVariosErrores
     */
    public function testLimpiarErrores(array $errores): void
    {
        foreach( $errores as $clave => $error ) {
            $this->assertFalse($this->error->clave($clave));
            $this->assertSame($error[ErrorAsociativo::CODIGO], $this->error->codigo($error[ErrorAsociativo::CODIGO]));
            $this->assertSame($error[ErrorAsociativo::MENSAJE], $this->error->mensaje($error[ErrorAsociativo::MENSAJE]));
        }

        $this->assertSame($errores, $this->error->lista());
        $this->assertTrue($this->error->hay());

        $this->error->limpiar();

        $this->assertEmpty($this->error->lista());
        $this->assertFalse($this->error->hay());
    }

    /**
     * @dataProvider dataVariosErrores
     */
    public function testClaveSoloDevuelveTrueSiExistenErroresAsociados(array $errores): void
    {
        $codigo = 1;
        $conjuntoDeClaves = array_keys($errores);

        foreach( $conjuntoDeClaves as $clave ) {
            $this->assertFalse($this->error->clave($clave));
        }

        // Por las dudas...
        foreach( $conjuntoDeClaves as $clave ) {
            $this->assertFalse($this->error->clave($clave));
        }

        foreach( $conjuntoDeClaves as $clave ) {
            $this->assertFalse($this->error->clave($clave));
            $this->assertSame($codigo, $this->error->codigo($codigo));
        }

        foreach( $conjuntoDeClaves as $clave ) {
            $this->assertTrue($this->error->clave($clave));
        }
    }

    public function dataVariosErrores(): array
    {
        return [
            [[
                'http_status_1' => [
                    ErrorAsociativo::CODIGO => 500,
                    ErrorAsociativo::MENSAJE => 'Error interno del servidor'
                ],
                'http_status_2' => [
                    ErrorAsociativo::CODIGO => 404,
                    ErrorAsociativo::MENSAJE => 'Página inexistente'
                ],
            ]],
        ];
    }

    public function testClavePorDefectoSiNoSeEspecificaEsCero(): void
    {
        $claveEsperada = '0';
        $mensaje = 'satán';
        $codigo = 666;

        $this->assertSame($codigo, $this->error->codigo($codigo));
        $this->assertSame($mensaje, $this->error->mensaje($mensaje));

        $listaDeErrorEsperada = [
            $claveEsperada => [
                ErrorAsociativo::CODIGO => $codigo,
                ErrorAsociativo::MENSAJE => $mensaje
            ]
        ];

        $this->assertSame($listaDeErrorEsperada, $this->error->lista());
    }

    public function testValoresQueEntreganSiNoExisteNadaRegistrado(): void
    {
        $claveInexistente = 'clave_inexistente';
        $this->assertFalse($this->error->clave($claveInexistente));

        $codigoDeErrorQueDevuelveSiNoExisteNadaRegistrado = 0;
        $mensajeDeErrorQueDevuelveSiNoExisteNadaRegistrado = '';

        $this->assertSame($codigoDeErrorQueDevuelveSiNoExisteNadaRegistrado, $this->error->codigo());
        $this->assertSame($mensajeDeErrorQueDevuelveSiNoExisteNadaRegistrado, $this->error->mensaje());

        $this->assertFalse($this->error->clave($claveInexistente));
    }

}
