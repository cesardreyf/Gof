<?php

declare(strict_types=1);

namespace Test\Sistema\Formulario\Datos\Campo;

use Gof\Sistema\Formulario\Datos\Campo\TipoInt;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Interfaz\ErroresMensaje;
use Gof\Sistema\Formulario\Interfaz\Tipos;
use Gof\Sistema\Formulario\Validar\ValidarLimiteInt;
use PHPUnit\Framework\TestCase;
use stdClass;

class TipoIntTest extends TestCase
{

    public function testValidarQueElTipoCorresponda(): void
    {
        $entero = new TipoInt('validando_el_tipo');
        $this->assertSame(Tipos::TIPO_INT, $entero->tipo());
    }

    public function testValidarCamposVaciosNulos(): void
    {
        $entero = new TipoInt('campo_de_tipo_int');
        $this->assertNull($entero->valor());
        $this->assertFalse($entero->error()->hay());
        $this->assertFalse($entero->validar());
    }

    /**
     * @dataProvider dataValoresParaCamposQueNoSonInt
     */
    public function testValidarCamposDevuelveFalse(mixed $valor): void
    {
        $entero = new TipoInt('campo_de_tipo_int_que_no_es_un_int');
        $entero->valor = $valor;

        $this->assertFalse($entero->error()->hay());
        $this->assertFalse($entero->validar());
        $this->assertTrue($entero->error()->hay());

        $codigoDeErrorEsperado  = Errores::ERROR_NO_ES_INT;
        $mensajeDeErrorEsperado = ErroresMensaje::NO_ES_INT;

        $this->assertSame($codigoDeErrorEsperado,  $entero->error()->codigo());
        $this->assertSame($mensajeDeErrorEsperado, $entero->error()->mensaje());
    }

    public function dataValoresParaCamposQueNoSonInt(): array
    {
        return [
            [PHP_FLOAT_MAX],
            [PHP_FLOAT_MIN],

            ['1.234567890'],
            ['123456siete'],
            ['1234cinco67'],
            ['uno23456789'],
            ['uno2345seis'],
            ['12E4S67890'],

            [new stdClass()],
            [[1234567890]],
            [null],
            [''],
        ];
    }

    /**
     * @dataProvider dataValoresParaCamposQueSiSonInt
     */
    public function testValidarCamposDevuelveTrue(mixed $valor): void
    {
        $entero = new TipoInt('campo_de_tipo_int_que_si_es_un_int');
        $entero->valor = $valor;

        $this->assertFalse($entero->error()->hay());
        $this->assertTrue($entero->validar());
        $this->assertFalse($entero->error()->hay());
    }

    public function dataValoresParaCamposQueSiSonInt(): array
    {
        return [
            [PHP_INT_MIN],
            [PHP_INT_MAX],
            ['1234567890'],
            ['-123456789'],
            ['0'],

            ['  1234567890  '],
            ['  -123456789  '],
            ['  0  '],
        ];
    }

    public function testMetodoValidarLimite(): void
    {
        $entero = new TipoInt('');
        $this->assertInstanceOf(ValidarLimiteInt::class, $entero->limite());
    }

    public function testRevalidarDatosDelCampo(): TipoInt
    {
        $entero = new TipoInt('');
        $noSoyUnInt = PHP_FLOAT_MAX;
        $siSoyUnInt = PHP_INT_MAX;

        $entero->valor = $noSoyUnInt;
        $this->assertFalse($entero->validar());
        $this->assertTrue($entero->error()->hay());

        $entero->valor = $siSoyUnInt;
        $this->assertTrue($entero->validar());
        return $entero;
    }

    /**
     * @depends testRevalidarDatosDelCampo
     */
    public function testRevalidarDatosDelCampoNoLimpiaLosErrores(TipoInt $entero): void
    {
        $this->assertTrue($entero->error()->hay());
    }
}
