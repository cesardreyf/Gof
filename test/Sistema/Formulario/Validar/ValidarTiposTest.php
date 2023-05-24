<?php

declare(strict_types=1);

namespace Test\Sistema\Formulario\Validar;

use Gof\Datos\Formulario\Campo;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Interfaz\Tipos;
use Gof\Sistema\Formulario\Validar\ValidarTipos;
use PHPUnit\Framework\TestCase;
use stdClass;

class ValidarTiposTest extends TestCase
{

    public function testNoValidarSiHayErroresPreviosEnElCampo(): void
    {
        $campo = new Campo('algo', Tipos::TIPO_STRING);
        $campo->valor = 'string';

        $cualquierCodigoDeError = 1;
        $campo->error()->codigo($cualquierCodigoDeError);
        $this->assertTrue($campo->error()->hay());

        $validacionDeTipo = new ValidarTipos($campo);
        $this->assertNull($validacionDeTipo->valido());
    }

    /**
     * @dataProvider dataCamposStringConClaveValor
     */
    public function testValidarStringValidos(string $clave, mixed $valor): void
    {
        $campo = new Campo($clave, Tipos::TIPO_STRING);
        $campo->valor = $valor;

        $validacionDeTipo = new ValidarTipos($campo);
        $this->assertTrue($validacionDeTipo->valido());
        $this->assertFalse($campo->error()->hay());
    }

    public function dataCamposStringConClaveValor(): array
    {
        return [
            ['string_ascii', 'abcdefghijklmnopqrstuvwxyz0987654321'],
            ['string_especial', '-_!"#$%&/()=?¡¿*][}{,.<>°|~'],
            ['string_hispano', 'áéíóúüÁÉÍÓÚÜñÑ'],
            // Continuará
        ];
    }

    /**
     * @dataProvider dataCamposStringConClaveValorIncorrectos
     */
    public function testValidarStringInvalidos(string $clave, mixed $valor): void
    {
        $campo = new Campo($clave, Tipos::TIPO_STRING);
        $campo->valor = $valor;

        $validacionDeTipo = new ValidarTipos($campo);
        $this->assertFalse($validacionDeTipo->valido());

        $codigoDeErrorEsperado = Errores::ERROR_NO_ES_STRING;
        $mensajeDeErrorEsperado = ValidarTipos::NO_ES_STRING;

        $this->assertSame($codigoDeErrorEsperado, $campo->error()->codigo());
        $this->assertSame($mensajeDeErrorEsperado, $campo->error()->mensaje());
    }

    public function dataCamposStringConClaveValorIncorrectos(): array
    {
        return [
            ['integer', PHP_INT_MAX],
            ['float', PHP_FLOAT_MAX],
            ['array_con_string', ['string']],
            ['object', new stdClass()],
        ];
    }

    /**
     * @dataProvider dataCamposIntConClaveValor
     */
    public function testValidarIntValidos(string $clave, mixed $valor): void
    {
        $campo = new Campo($clave, Tipos::TIPO_INT);
        $campo->valor = $valor;

        $validacionDeTipo = new ValidarTipos($campo);
        $this->assertTrue($validacionDeTipo->valido());
        $this->assertFalse($campo->error()->hay());
    }

    public function dataCamposIntConClaveValor(): array
    {
        return [
            ['int_positivo', PHP_INT_MAX],
            ['int_negativo', PHP_INT_MIN],
            ['string_int_postivo', '1234567890'],
            ['string_int_negativo', '-1234567890'],
        ];
    }

    /**
     * @dataProvider dataCamposIntConClaveValorIncorrectos
     */
    public function testValidarIntInvalidos(string $clave, mixed $valor): void
    {
        $campo = new Campo($clave, Tipos::TIPO_INT);
        $campo->valor = $valor;

        $validacionDeTipo = new ValidarTipos($campo);
        $this->assertFalse($validacionDeTipo->valido());

        $codigoDeErrorEsperado = Errores::ERROR_NO_ES_INT;
        $mensajeDeErrorEsperado = ValidarTipos::NO_ES_INT;

        $this->assertSame($codigoDeErrorEsperado, $campo->error()->codigo());
        $this->assertSame($mensajeDeErrorEsperado, $campo->error()->mensaje());
    }

    public function dataCamposIntConClaveValorIncorrectos(): array
    {
        return [
            ['string', 'uno'],
            ['string_int', 'uno23'],
            ['string_int_string', 'uno23cuatro'],

            ['string_menos_na', '-'],

            ['float', PHP_FLOAT_MAX],
            ['array_int', [PHP_INT_MAX]],
            ['object', new stdClass()],
        ];
    }

}
