<?php

declare(strict_types=1);

namespace Test\Sistema\Formulario\Validar;

use Gof\Sistema\Formulario\Datos\Campo;
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
     * @dataProvider dataCamposValidos
     */
    public function testValidarCamposValidos(int $tipo, string $clave, mixed $valor): void
    {
        $campo = new Campo($clave, $tipo);
        $campo->valor = $valor;

        $validacionDeTipo = new ValidarTipos($campo);
        $this->assertTrue($validacionDeTipo->valido());
        $this->assertFalse($campo->error()->hay());
    }

    /**
     * @dataProvider dataCamposInvalidos
     */
    public function testValidarCamposConValoresIncorrectos(int $tipo, int $codigoDeErrorEsperado, string $mensajeDeErrorEsperado, string $clave, mixed $valor): void
    {
        $campo = new Campo($clave, $tipo);
        $campo->valor = $valor;

        $validacionDeTipo = new ValidarTipos($campo);
        $this->assertFalse($validacionDeTipo->valido());

        $this->assertSame($codigoDeErrorEsperado, $campo->error()->codigo());
        $this->assertSame($mensajeDeErrorEsperado, $campo->error()->mensaje());
    }

    public function dataCamposValidos(): array
    {
        return [
            [Tipos::TIPO_STRING, 'string_ascii',    'abcdefghijklmnopqrstuvwxyz0987654321'],
            [Tipos::TIPO_STRING, 'string_especial', '-_!"#$%&/()=?¡¿*][}{,.<>°|~'],
            [Tipos::TIPO_STRING, 'string_hispano',  'áéíóúüÁÉÍÓÚÜñÑ'],

            [Tipos::TIPO_INT, 'int_positivo', PHP_INT_MAX],
            [Tipos::TIPO_INT, 'int_negativo', PHP_INT_MIN],
            [Tipos::TIPO_INT, 'string_int_postivo', '1234567890'],
            [Tipos::TIPO_INT, 'string_int_negativo', '-1234567890'],
            [Tipos::TIPO_INT, 'string_int_con_espacios_a_la_izquierda', ' 1234567890'],
            [Tipos::TIPO_INT, 'string_int_con_espacios_a_la_derecha', '1234567890 '],
            [Tipos::TIPO_INT, 'string_int_con_espacios_al_rededor', ' 1234567890 '],

            [Tipos::TIPO_ARRAY, 'array_unidimensional', ['algo']],
            [Tipos::TIPO_ARRAY, 'array_bidimensional', ['algo' => ['bobo']]],
        ];
    }

    public function dataCamposInvalidos(): array
    {
        return [
            [Tipos::TIPO_STRING, Errores::ERROR_NO_ES_STRING, ValidarTipos::NO_ES_STRING, 'integer', PHP_INT_MAX],
            [Tipos::TIPO_STRING, Errores::ERROR_NO_ES_STRING, ValidarTipos::NO_ES_STRING, 'float', PHP_FLOAT_MAX],
            [Tipos::TIPO_STRING, Errores::ERROR_NO_ES_STRING, ValidarTipos::NO_ES_STRING, 'array_con_string', ['string']],
            [Tipos::TIPO_STRING, Errores::ERROR_NO_ES_STRING, ValidarTipos::NO_ES_STRING, 'object', new stdClass()],

            [Tipos::TIPO_INT, Errores::ERROR_NO_ES_INT, ValidarTipos::NO_ES_INT, 'string', 'uno'],
            [Tipos::TIPO_INT, Errores::ERROR_NO_ES_INT, ValidarTipos::NO_ES_INT, 'string_int', 'uno23'],
            [Tipos::TIPO_INT, Errores::ERROR_NO_ES_INT, ValidarTipos::NO_ES_INT, 'string_int_string', 'uno23cuatro'],
            [Tipos::TIPO_INT, Errores::ERROR_NO_ES_INT, ValidarTipos::NO_ES_INT, 'string_menos_na', '-'],
            [Tipos::TIPO_INT, Errores::ERROR_NO_ES_INT, ValidarTipos::NO_ES_INT, 'float', PHP_FLOAT_MAX],
            [Tipos::TIPO_INT, Errores::ERROR_NO_ES_INT, ValidarTipos::NO_ES_INT, 'array_int', [PHP_INT_MAX]],
            [Tipos::TIPO_INT, Errores::ERROR_NO_ES_INT, ValidarTipos::NO_ES_INT, 'object', new stdClass()],

            [Tipos::TIPO_ARRAY, Errores::ERROR_NO_ES_ARRAY, ValidarTipos::NO_ES_ARRAY, 'integer', PHP_INT_MAX],
            [Tipos::TIPO_ARRAY, Errores::ERROR_NO_ES_ARRAY, ValidarTipos::NO_ES_ARRAY, 'float', PHP_FLOAT_MAX],
            [Tipos::TIPO_ARRAY, Errores::ERROR_NO_ES_ARRAY, ValidarTipos::NO_ES_ARRAY, 'string', '[algo]'],
            [Tipos::TIPO_ARRAY, Errores::ERROR_NO_ES_ARRAY, ValidarTipos::NO_ES_ARRAY, 'object', new stdClass()],
        ];
    }

}
