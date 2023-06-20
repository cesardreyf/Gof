<?php

declare(strict_types=1);

namespace Test\Sistema\Formulario\Datos\Campo;

use Gof\Sistema\Formulario\Datos\Campo\TipoString;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Interfaz\ErroresMensaje;
use Gof\Sistema\Formulario\Interfaz\Tipos;
use Gof\Sistema\Formulario\Validar\ValidarLongitud;
use PHPUnit\Framework\TestCase;
use stdClass;

class TipoStringTest extends TestCase
{

    public function testValidarQueElTipoCorresponda(): void
    {
        $vector = new TipoString('validando_el_tipo');
        $this->assertSame(Tipos::TIPO_STRING, $vector->tipo());
    }

    /**
     * @dataProvider dataValoresDeTipoStringValidos
     */
    public function testValidarDevuelveTrue(mixed $valor): void
    {
        $cadena = new TipoString('campo_de_tipo_string_valido');
        $this->assertFalse($cadena->error()->hay());

        $cadena->valor = $valor;

        $this->assertTrue($cadena->validar());
        $this->assertFalse($cadena->error()->hay());
    }

    public function dataValoresDeTipoStringValidos(): array
    {
        return [
            ['-_!"#$%&/() =?¡¿*][}{,.<>°|~'],
            ['abcdefghijklmnopqrstuvwxyz'],
            ['ñÑáéíóúÁÉÍÓÚöÖ'],
            ['hola mundo'],
            [' '],
            [''],
        ];
    }

    /**
     * @dataProvider dataValoresDeTipoStringInvalidos
     */
    public function testValidarDevuelveFalse(mixed $valor): void
    {
        $cadena = new TipoString('campo_de_tipo_string_invalido');
        $this->assertFalse($cadena->error()->hay());

        $cadena->valor = $valor;

        $this->assertFalse($cadena->validar());
        $this->assertTrue($cadena->error()->hay());

        $codigoDeErrorEsperado = Errores::ERROR_NO_ES_STRING;
        $mensajeDeErrorEsperado = ErroresMensaje::NO_ES_STRING;

        $this->assertSame($codigoDeErrorEsperado, $cadena->error()->codigo());
        $this->assertSame($mensajeDeErrorEsperado, $cadena->error()->mensaje());
    }

    public function dataValoresDeTipoStringInvalidos(): array
    {
        return [
            [PHP_INT_MAX],
            [PHP_FLOAT_MAX],
            [null],
            [true],
            [false],
            [new stdClass()],
            [['string_dentro_de_un_array']],
        ];
    }

    public function testValidarDevuelveNullSiHayErroresPrevios(): void
    {
        $cadena = new TipoString('campo_de_tipo_string_con_errores_previos');
        $this->assertFalse($cadena->error()->hay());

        $cadena->valor = [];
        $this->assertFalse($cadena->validar());

        $cadena->valor = new stdClass();
        $this->assertNull($cadena->validar());
    }

    public function testMetodoValidarLongitud(): void
    {
        $cadena = new TipoString('');
        $this->assertInstanceOf(ValidarLongitud::class, $cadena->longitud());
    }


}
