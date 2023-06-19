<?php

declare(strict_types=1);

namespace Test\Sistema\Formulario\Datos\Campo;

use Gof\Sistema\Formulario\Datos\Campo;
use Gof\Sistema\Formulario\Datos\Campo\TipoTabla;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Interfaz\ErroresMensaje;
use Gof\Sistema\Formulario\Interfaz\Tipos;
use PHPUnit\Framework\TestCase;
use stdClass;

class TipoTablaTest extends TestCase
{

    public function testExtiendeDeCampo(): void
    {
        $tabla = new TipoTabla('');
        $this->assertInstanceOf(Campo::class, $tabla);
    }

    public function testTipoDeCampoImplicito(): void
    {
        $tabla = new TipoTabla('');
        $this->assertSame(Tipos::TIPO_TABLA, $tabla->tipo());
    }

    /**
     * @dataProvider dataNombreDeColumnasYTipos
     */
    public function testAgregarYObtenerColumnasYSusTipos(array $columnasYTipos): void
    {
        $tabla = new TipoTabla('');
        $this->assertEmpty($tabla->obtenerColumnas());

        foreach( $columnasYTipos as $nombreDeLaColumna => $tipo ) {
            $tabla->columna($nombreDeLaColumna, $tipo);
        }

        $this->assertSame($columnasYTipos, $tabla->obtenerColumnas());
    }

    public function dataNombreDeColumnasYTipos(): array
    {
        return [
            [[]],
            [[
                'columna_de_tipo_int' => Tipos::TIPO_INT,
                'columna_de_tipo_array' => Tipos::TIPO_ARRAY,
                'columna_de_tipo_string' => Tipos::TIPO_STRING,
            ]],
        ];
    }

    /**
     * @dataProvider dataNombreDeColumnaConTipoViejaYNueva
     */
    public function testReagregarUnCampoReemplazaElTipo(string $columna, int $tipoVieja, int $tipoNueva): void
    {
        $tabla = new TipoTabla('');
        $this->assertEmpty($tabla->obtenerColumnas());

        $tabla->columna($columna, $tipoVieja);
        $this->assertSame([$columna => $tipoVieja], $tabla->obtenerColumnas());

        $tabla->columna($columna, $tipoNueva);
        $this->assertNotSame($tipoVieja, $tipoNueva);
        $this->assertCount(1, $tabla->obtenerColumnas());
        $this->assertSame([$columna => $tipoNueva], $tabla->obtenerColumnas());
    }

    public function dataNombreDeColumnaConTipoViejaYNueva(): array
    {
        return [
            ['columna_que_cambia_de_tipo_int_a_tipo_string', Tipos::TIPO_INT, Tipos::TIPO_STRING],
        ];
    }

    /**
     * @dataProvider dataValoresValidos
     */
    public function testValidarDevuelveTrue(array $valor, array $columnasYTipo): void
    {
        $tabla = new TipoTabla('');
        $tabla->valor = $valor;

        foreach( $columnasYTipo as $columna => $tipo ) {
            $tabla->columna($columna, $tipo);
        }

        $this->assertFalse($tabla->error()->hay());
        $this->assertTrue($tabla->validar());
        $this->assertFalse($tabla->error()->hay());
    }

    public function dataValoresValidos(): array
    {
        return [
            [
                // Tabla
                [
                    // Fila #0
                    [
                        'columna_de_tipo_int' => PHP_INT_MAX,
                        'columna_de_tipo_array' => [true],
                        'columna_de_tipo_string' => '',
                    ],
                ],
                // Columnas
                [
                    'columna_de_tipo_int' => Tipos::TIPO_INT,
                    'columna_de_tipo_array' => Tipos::TIPO_ARRAY,
                    'columna_de_tipo_string' => Tipos::TIPO_STRING,
                ]
            ],
            [
                // Tabla
                [
                    // Fila #0
                    [
                        'columna_sobrante' => 'sobrando',
                        'columna_obligatoria' => PHP_INT_MAX
                    ],
                ],
                // Columnas
                [
                    'columna_obligatoria' => Tipos::TIPO_INT
                ]
            ],
        ];
    }

    /**
     * @dataProvider dataValoresDiferentesDeArray
     * @dataProvider dataCampoVacio
     * @dataProvider dataFilasQueNoSonArrays
     * @dataProvider dataFilasQueNoContienenTodasLasColumnasObligatorias
     * @dataProvider dataValoresDeColumnasInvalidas
     */
    public function testValidarDevuelveFalse(mixed $valor, array $columnasYTipos, int $codigoDeErrorEsperado, string $mensajeDeErrorEsperado): void
    {
        $tabla = new TipoTabla('');
        $tabla->valor = $valor;

        foreach( $columnasYTipos as $columna => $tipo ) {
            $tabla->columna($columna, $tipo);
        }

        $this->assertFalse($tabla->error()->hay());
        $this->assertFalse($tabla->validar());
        $this->assertTrue($tabla->error()->hay());

        $this->assertSame($mensajeDeErrorEsperado, $tabla->error()->mensaje());
        $this->assertSame($codigoDeErrorEsperado, $tabla->error()->codigo());
    }

    public function dataValoresDiferentesDeArray(): array
    {
        return [
            [null,           [], Errores::ERROR_NO_ES_TABLA, ErroresMensaje::NO_ES_TABLA],
            [true,           [], Errores::ERROR_NO_ES_TABLA, ErroresMensaje::NO_ES_TABLA],
            [false,          [], Errores::ERROR_NO_ES_TABLA, ErroresMensaje::NO_ES_TABLA],
            ['algo',         [], Errores::ERROR_NO_ES_TABLA, ErroresMensaje::NO_ES_TABLA],
            [PHP_INT_MAX,    [], Errores::ERROR_NO_ES_TABLA, ErroresMensaje::NO_ES_TABLA],
            [PHP_FLOAT_MAX,  [], Errores::ERROR_NO_ES_TABLA, ErroresMensaje::NO_ES_TABLA],
            [new stdClass(), [], Errores::ERROR_NO_ES_TABLA, ErroresMensaje::NO_ES_TABLA],
        ];
    }

    public function dataCampoVacio(): array
    {
        return [
            [[], [], Errores::ERROR_CAMPO_VACIO, ErroresMensaje::CAMPO_VACIO]
        ];
    }

    public function dataFilasQueNoSonArrays(): array
    {
        return [
            [
                [[], [], PHP_INT_MAX],
                [],
                Errores::ERROR_FILAS_INVALIDAS,
                TipoTabla::FILAS_INVALIDAS
            ],
            [
                [PHP_FLOAT_MAX, true, [], [], []],
                [],
                Errores::ERROR_FILAS_INVALIDAS,
                TipoTabla::FILAS_INVALIDAS
            ],
        ];
    }

    public function dataFilasQueNoContienenTodasLasColumnasObligatorias(): array
    {
        return [
            [
                [
                    ['col1' => PHP_INT_MAX, 'otra_columna' => 'otra cosa'],
                    ['col2' => 'no', 'cita' => 'bla bla']
                ],
                [
                    'col1' => Tipos::TIPO_INT,
                    'col2' => Tipos::TIPO_STRING
                ],
                Errores::ERROR_COLUMNAS_FALTAN,
                TipoTabla::COLUMNAS_FALTAN
            ],
        ];
    }

    public function dataValoresDeColumnasInvalidas(): array
    {
        return [
            [
                [
                    ['columnas_de_tipo_int' => PHP_FLOAT_MAX],
                ],
                [
                    'columnas_de_tipo_int' => Tipos::TIPO_INT
                ],
                Errores::ERROR_COLUMNAS_INVALIDAS,
                TipoTabla::COLUMNAS_INVALIDAS
            ],
        ];
    }

}
