<?php

declare(strict_types=1);

namespace Test\Sistema\Formulario\Datos\Campo;

use Gof\Sistema\Formulario\Datos\Campo\TipoArray;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Interfaz\ErroresMensaje;
use Gof\Sistema\Formulario\Interfaz\Tipos;
use Gof\Sistema\Formulario\Mediador\Campo\Error;
use PHPUnit\Framework\TestCase;
use stdClass;

class TipoArrayTest extends TestCase
{

    public function testValidarQueElTipoCorresponda(): void
    {
        $vector = new TipoArray('validando_el_tipo');
        $this->assertSame(Tipos::TIPO_ARRAY, $vector->tipo());
    }

    /**
     * @dataProvider dataValoresValidosParaElTipoArray
     */
    public function testValidarArrayConValoresValidos(mixed $valor): void
    {
        $vector = new TipoArray('campo_de_tipo_array');
        $this->assertFalse($vector->error()->hay());

        $vector->valor = $valor;
        $this->assertTrue($vector->validar());
        $this->assertFalse($vector->error()->hay());
    }

    public function dataValoresValidosParaElTipoArray(): array
    {
        return [
            [[]],
            [['algo']],
            [['algo' => 'bobo']],
        ];
    }

    /**
     * @dataProvider dataValoresInvalidosParaElTipoArray
     */
    public function testValidarArrayConValoresInvalidos(mixed $valor): void
    {
        $vector = new TipoArray('campo_de_tipo_array_no_valido');
        $this->assertFalse($vector->error()->hay());

        $vector->valor = $valor;
        $this->assertFalse($vector->validar());
        $this->assertTrue($vector->error()->hay());
    }

    public function dataValoresInvalidosParaElTipoArray(): array
    {
        return [
            ['algo[bobo]'],
            [PHP_INT_MAX],
            [PHP_FLOAT_MAX],
            [new stdClass()],
            [false],
            [null],
            [true],
        ];
    }

    public function testRevalidarDatosDelCampo(): TipoArray
    {
        $vector = new TipoArray('');
        $noSoyUnArray = PHP_INT_MAX;
        $siSoyUnArray = [];

        $vector->valor = $noSoyUnArray;
        $this->assertFalse($vector->validar());
        $this->assertTrue($vector->error()->hay());

        $vector->valor = $siSoyUnArray;
        $this->assertTrue($vector->validar());
        return $vector;
    }

    /**
     * @depends testRevalidarDatosDelCampo
     */
    public function testRevalidarDatosDelCampoNoLimpiaLosErrores(TipoArray $vector): void
    {
        $this->assertTrue($vector->error()->hay());
    }

}
