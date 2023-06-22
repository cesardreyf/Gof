<?php

declare(strict_types=1);

namespace Test\Sistema\Formulario\Datos\Campo;

use Gof\Sistema\Formulario\Datos\Campo;
use Gof\Sistema\Formulario\Datos\Campo\TipoFloat;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Interfaz\ErroresMensaje;
use Gof\Sistema\Formulario\Interfaz\Tipos;
use Gof\Sistema\Formulario\Validar\ValidarLimiteFloat;
use PHPUnit\Framework\TestCase;
use stdClass;

class TipoFloatTest extends TestCase
{
    private TipoFloat $float;

    public function setUp(): void
    {
        $this->float = new TipoFloat('');
    }

    public function testEsUnaInstanciaDeCampo(): void
    {
        $this->assertInstanceOf(Campo::class, $this->float);
    }

    public function testTipoDelCampo(): void
    {
        $this->assertSame(Tipos::TIPO_FLOAT, $this->float->tipo());
    }

    /**
     * @dataProvider dataNumeroFloat
     * @dataProvider dataStringFloat
     */
    public function testValidarDevuelveTrue(mixed $valor): void
    {
        $this->float->valor = $valor;

        $this->assertFalse($this->float->error()->hay());
        $this->assertTrue($this->float->validar());
        $this->assertFalse($this->float->error()->hay());
    }

    public function dataNumeroFloat(): array
    {
        return [
            [PHP_FLOAT_MAX],
            [PHP_FLOAT_MIN],
            [123E4],
        ];
    }

    public function dataStringFloat(): array
    {
        return [
            ['123.4567889'],
            ['-123.456788'],
            ['-123E4'],
            ['123E4'],
        ];
    }

    /**
     * @dataProvider dataValoresDiferentesDeFloat
     * @dataProvider dataCampoVacio
     */
    public function testValidarDevuelveFalse(mixed $valor, int $codigoDeErrorEsperado, string $mensajeDeErrorEsperado): void
    {
        $this->float->valor = $valor;

        $this->assertFalse($this->float->error()->hay());
        $this->assertFalse($this->float->validar());

        $this->assertTrue($this->float->error()->hay());
        $this->assertNotEmpty($this->float->error()->lista());

        $this->assertSame($mensajeDeErrorEsperado, $this->float->error()->mensaje());
        $this->assertSame($codigoDeErrorEsperado, $this->float->error()->codigo());
    }

    public function dataValoresDiferentesDeFloat(): array
    {
        return [
            [null, Errores::ERROR_NO_ES_FLOAT, ErroresMensaje::NO_ES_FLOAT],
            [true, Errores::ERROR_NO_ES_FLOAT, ErroresMensaje::NO_ES_FLOAT],
            [false, Errores::ERROR_NO_ES_FLOAT, ErroresMensaje::NO_ES_FLOAT],
            [PHP_INT_MAX, Errores::ERROR_NO_ES_FLOAT, ErroresMensaje::NO_ES_FLOAT],
            [new stdClass(), Errores::ERROR_NO_ES_FLOAT, ErroresMensaje::NO_ES_FLOAT],

            ['hola_mundo', Errores::ERROR_NO_ES_FLOAT, ErroresMensaje::NO_ES_FLOAT],
            [['soy_un_array'], Errores::ERROR_NO_ES_FLOAT, ErroresMensaje::NO_ES_FLOAT],
            [['float_dentro_de_un_array'], Errores::ERROR_NO_ES_FLOAT, ErroresMensaje::NO_ES_FLOAT],
        ];
    }

    public function dataCampoVacio(): array
    {
        return [
            ['', Errores::ERROR_CAMPO_VACIO, ErroresMensaje::CAMPO_VACIO],
            ['   ', Errores::ERROR_CAMPO_VACIO, ErroresMensaje::CAMPO_VACIO],
        ];
    }

    public function testMetodoValidarLimite(): void
    {
        $this->assertInstanceOf(ValidarLimiteFloat::class, $this->float->limite());
    }

    public function testRevalidarDatosDelCampo(): TipoFloat
    {
        $noSoyUnFloat = PHP_INT_MAX;
        $siSoyUnFloat = PHP_FLOAT_MAX;

        $this->float->valor = $noSoyUnFloat;
        $this->assertFalse($this->float->validar());
        $this->assertTrue($this->float->error()->hay());

        $this->float->valor = $siSoyUnFloat;
        $this->assertTrue($this->float->validar());
        return $this->float;
    }

    /**
     * @depends testRevalidarDatosDelCampo
     */
    public function testRevalidarDatosDelCampoNoLimpiaLosErrores(TipoFloat $float): void
    {
        $this->assertTrue($float->error()->hay());
    }
}
