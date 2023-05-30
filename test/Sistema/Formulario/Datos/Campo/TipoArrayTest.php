<?php

declare(strict_types=1);

namespace Test\Sistema\Formulario\Datos\Campo;

use Gof\Sistema\Formulario\Datos\Campo\TipoArray;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Interfaz\ErroresMensaje;
use Gof\Sistema\Formulario\Mediador\Campo\Error;
use PHPUnit\Framework\TestCase;
use stdClass;

class TipoArrayTest extends TestCase
{

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

    public function testValidarConErroresPreviosDevuelveNull(): void
    {
        $vector = new TipoArray('campo_de_tipo_array');
        $this->assertFalse($vector->error()->hay());

        $vector->valor = PHP_INT_MAX;
        $this->assertFalse($vector->validar());

        $vector->valor = PHP_FLOAT_MAX;
        $this->assertNull($vector->validar());
    }

}
