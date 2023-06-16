<?php

declare(strict_types=1);

namespace Test\Sistema\Formulario\Gestor;

use Gof\Interfaz\Errores\Mensajes\Error;
use Gof\Sistema\Formulario\Contratos\Errores as ErroresInterfaz;
use Gof\Sistema\Formulario\Gestor\Errores;
use Gof\Sistema\Formulario\Interfaz\Campo;
use PHPUnit\Framework\TestCase;

class ErroresTest extends TestCase
{
    public const ERROR_MENSAJE_1 = 'primer mensaje de error';
    public const ERROR_MENSAJE_2 = 'segundo mensaje de error';

    /**
     * @dataProvider dataCampoConError
     */
    public function testHayErrores(Campo $campoConError): void
    {
        $campos = [$campoConError];
        $errores = new Errores($campos);

        $this->assertTrue($errores->hay());
        $this->assertNotEmpty($errores->lista());
    }

    /**
     * @dataProvider dataCampoConError
     */
    public function testRecorrerListaDeCamposEnBusquedaDeErroresYObtenerUnaLista(Campo $campoConError): void
    {
        $campos = [$campoConError];
        $errores = new Errores($campos);

        $this->assertNotEmpty($errores->lista());
        $this->assertCount(1, $errores->lista());

        $listaDeErrorEsperada = [self::ERROR_MENSAJE_1];
        $this->assertSame($listaDeErrorEsperada, $errores->lista());
    }

    /**
     * @dataProvider dataCampoConError
     */
    public function testActualizarCacheDeErrores(Campo $campoConError): void
    {
        $listaDeCampos = [$campoConError];
        $errores = new Errores($listaDeCampos);
        $erroresCacheados = [self::ERROR_MENSAJE_1];
        $this->assertSame($erroresCacheados, $errores->lista());
        $this->assertSame($erroresCacheados, $errores->lista());

        $errores->actualizarCache();
        $nuevosErroresCacheados = [self::ERROR_MENSAJE_2];
        $this->assertNotSame($erroresCacheados, $errores->lista());
        $this->assertSame($nuevosErroresCacheados, $errores->lista());
    }

    /**
     * @dataProvider dataCampoConError
     */
    public function testLimpiarLaCache(Campo $campoConError): void
    {
        $listaDeCampos = [$campoConError];
        $errores = new Errores($listaDeCampos);

        $this->assertNotEmpty($errores->lista());
        $errores->limpiar();

        $this->assertEmpty($errores->lista());
    }

    public function dataCampoConError(): array
    {
        $error = $this->createMock(Error::class);
        $campoConError = $this->createMock(Campo::class);

        $error
            ->expects($this->any())
            ->method('hay')
            ->willReturn(true);

        $error
            ->expects($this->any())
            ->method('limpiar');

        $error
            ->expects($this->any())
            ->method('mensaje')
            ->will(
                $this->onConsecutiveCalls(
                    self::ERROR_MENSAJE_1,
                    self::ERROR_MENSAJE_2
                )
            );

        $campoConError
            ->expects($this->any())
            ->method('error')
            ->willReturn($error);

        return [[$campoConError]];
    }

}
