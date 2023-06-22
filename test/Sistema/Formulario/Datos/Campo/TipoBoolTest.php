<?php

declare(strict_types=1);

namespace Test\Sistema\Formulario\Datos\Campo;

use Gof\Sistema\Formulario\Datos\Campo;
use Gof\Sistema\Formulario\Datos\Campo\TipoBool;
use Gof\Sistema\Formulario\Interfaz\Errores;
use PHPUnit\Framework\TestCase;
use stdClass;

class TipoBoolTest extends TestCase
{
    private TipoBool $check;

    public function setUp(): void
    {
        $this->check = new TipoBool('');
    }

    public function testExtenderDeCampo(): void
    {
        $this->assertInstanceOf(Campo::class, $this->check);
    }

    /**
     * @dataProvider dataValoresValidosParaUnCampoDeTipoBoolConElNuevoValorEsperado
     */
    public function testValidarDevuelveTrue(mixed $valor): void
    {
        $this->check->valor = $valor;
        $this->assertTrue($this->check->validar());
        $this->assertFalse($this->check->error()->hay());
    }

    /**
     * @dataProvider dataValoresValidosParaUnCampoDeTipoBoolConElNuevoValorEsperado
     */
    public function testValidarElCampoModificaElValorDelMismo(mixed $valor, bool $valorEsperado): void
    {
        $this->check->valor = $valor;
        $this->assertTrue($this->check->validar());
        $this->assertFalse($this->check->error()->hay());
        $this->assertSame($valorEsperado, $this->check->valor());
    }

    public function dataValoresValidosParaUnCampoDeTipoBoolConElNuevoValorEsperado(): array
    {
        return [
            [true,  true],
            [false, false],

            ['on',  true],
            ['off', false],

            ['si',  true],
            ['no',  false],

            ['1',   true],
            ['0',   false],
        ];
    }

    /**
     * @dataProvider dataValoresIncorrectosDeBool
     */
    public function testValidarDevuelveFalseYGeneraErroresEnElCampo(mixed $valor): void
    {
        $this->check->valor = $valor;
        $this->assertFalse($this->check->validar());
        $this->assertTrue($this->check->error()->hay());

        $this->assertSame(TipoBool::VALOR_INCORRECTO, $this->check->error()->mensaje());
        $this->assertSame(Errores::ERROR_NO_ES_BOOL, $this->check->error()->codigo());
    }

    public function dataValoresIncorrectosDeBool(): array
    {
        return [
            ['sí'],
            ['uwu'],
            [PHP_INT_MAX],
            [PHP_FLOAT_MAX],
            [null],
            [[true]],
            [[false]],
            [new stdClass],
        ];
    }

}
