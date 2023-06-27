<?php

declare(strict_types=1);

namespace Test\Sistema\Formulario\Gestor;

use Gof\Sistema\Formulario\Contratos\Errores as ErroresInterfaz;
use Gof\Sistema\Formulario\Datos\Campo\Error;
use Gof\Sistema\Formulario\Gestor\Errores;
use Gof\Sistema\Formulario\Gestor\Sistema;
use Gof\Sistema\Formulario\Interfaz\Campo;
use PHPUnit\Framework\TestCase;

class ErroresTest extends TestCase
{
    public const ERROR_MENSAJE_1 = 'primer mensaje de error';
    public const ERROR_MENSAJE_2 = 'segundo mensaje de error';

    /**
     * @dataProvider dataCampoConError
     */
    public function testHayErrores(Sistema $sistema): void
    {
        $errores = new Errores($sistema);

        $this->assertTrue($errores->hay());
        $this->assertNotEmpty($errores->lista());
    }

    /**
     * @dataProvider dataCampoConError
     */
    public function testRecorrerListaDeCamposEnBusquedaDeErroresYObtenerUnaLista(Sistema $sistema): void
    {
        $errores = new Errores($sistema);

        $this->assertNotEmpty($errores->lista());
        $this->assertCount(1, $errores->lista());

        $listaDeErrorEsperada = [self::ERROR_MENSAJE_1];
        $this->assertSame($listaDeErrorEsperada, $errores->lista());
    }

    /**
     * @dataProvider dataCampoConError
     */
    public function testActualizarCacheDeErrores(Sistema $sistema): void
    {
        $errores = new Errores($sistema);
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
    public function testLimpiarLaCache(Sistema $sistema): void
    {
        $errores = new Errores($sistema);

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
            ->method('obtener')
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

        $sistema = new Sistema();
        $sistema->campos = [$campoConError];
        return [[$sistema]];
    }

}
