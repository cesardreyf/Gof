<?php

declare(strict_types=1);

namespace Test\Sistema\Formulario\Datos\Campo;

use Gof\Datos\Lista\Texto\ListaDeTextos;
use Gof\Sistema\Formulario\Datos\Campo;
use Gof\Sistema\Formulario\Datos\Campo\TipoSelect;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Interfaz\ErroresMensaje;
use Gof\Sistema\Formulario\Interfaz\Tipos;
use PHPUnit\Framework\TestCase;
use stdClass;

class TipoSelectTest extends TestCase
{

    public function setUp(): void
    {
        $this->select = new TipoSelect('');
    }

    public function testExtiendeDeCampo(): void
    {
        $this->assertInstanceOf(Campo::class, $this->select);
    }

    public function testTipoSelectImplicito(): void
    {
        $this->assertSame(Tipos::TIPO_SELECT, $this->select->tipo());
    }

    /**
     * @dataProvider dataOpcionesValidas
     */
    public function testAgregarYObtenerUnaOpcion(string $nombre): void
    {
        $this->assertEmpty($this->select->opciones());
        $this->select->opcion($nombre);

        $this->assertCount(1, $this->select->opciones());
        $this->assertSame($nombre, $this->select->opciones()[0]);
    }

    public function dataOpcionesValidas(): array
    {
        return [
            ['option'],
            ['opción'],
            ['nombre con espacios'],
            ['nombre_con_barras_bajas'],
        ];
    }

    /**
     * @dataProvider dataConjuntoDeOpcionesValidas
     */
    public function testAgregarYObtenerVariasOpciones(array $opciones): void
    {
        foreach( $opciones as $nombreDeLaOpcion ) {
            $this->select->opcion($nombreDeLaOpcion);
        }

        $this->assertSame($opciones, $this->select->opciones());
    }

    public function dataConjuntoDeOpcionesValidas(): array
    {
        return [
            [
                ['opcion1', 'opcion 2', 'opcion_3', 'opciones varias', 'etc...'],
            ],
        ];
    }

    /**
     * @dataProvider dataConjuntoDeOpcionesValidas
     */
    public function testAgregarOpcionesDesdeUnaLista(array $opciones): void
    {
        $listaDeOpciones = $this->createMock(ListaDeTextos::class);
        $listaDeOpciones
            ->expects($this->once())
            ->method('lista')
            ->willReturn($opciones);
        $this->select->agregarOpcionesDesdeLista($listaDeOpciones);
        $this->assertSame($opciones, $this->select->opciones());
    }

    /**
     * @dataProvider dataValoresDiferentesDeString
     */
    public function testValidarValoresDiferntesDeStringDevuelveFalse(mixed $valor): void
    {
        $this->select->valor = $valor;
        $this->assertFalse($this->select->validar());

        $this->assertTrue($this->select->error()->hay());
        $this->assertSame(ErroresMensaje::NO_ES_SELECT, $this->select->error()->mensaje());
        $this->assertSame(Errores::ERROR_NO_ES_SELECT, $this->select->error()->codigo());
    }

    public function dataValoresDiferentesDeString(): array
    {
        return [
            [PHP_INT_MAX],
            [PHP_FLOAT_MAX],
            [[]],
            [false],
            [true],
            [null],
            [new stdClass()],
        ];
    }

    public function testValidarDevuelveFalseSiElCampoEstaVacio(): void
    {
        $this->select->valor = '';
        $this->assertFalse($this->select->validar());
        $this->assertSame(ErroresMensaje::CAMPO_VACIO, $this->select->error()->mensaje());
        $this->assertSame(Errores::ERROR_CAMPO_VACIO, $this->select->error()->codigo());
    }

    /**
     * @dataProvider dataValorYOpcionesValidas
     */
    public function testValidarDevuelveTrueSiLaOpcionFormaParteDelConjuntoDeOpcionesValidas(array $opcionesValidas): void
    {
        foreach( $opcionesValidas as $opcionValida ) {
            $this->select->opcion($opcionValida);
        }

        foreach( $opcionesValidas as $opcionValida ) {
            $this->select->valor = $opcionValida;
            $this->assertTrue($this->select->validar());
            $this->assertFalse($this->select->error()->hay());
        }
    }

    public function dataValorYOpcionesValidas(): array
    {
        return [
            [
                [
                    '0',
                    'opcion valida',
                    'opción_válida',
                ]
            ],
        ];
    }

    /**
     * @dataProvider dataValoresInvalidosConSusErrores
     */
    public function testValidarDevuelveFalse(mixed $valor, array $opcionesValidas, int $codigoDeErrorEsperado, string $mensajeDeErrorEsperado): void
    {
        $this->select->valor = $valor;

        foreach( $opcionesValidas as $opcionValida ) {
            $this->select->opcion($opcionValida);
        }

        $this->assertFalse($this->select->validar());
        $this->assertTrue($this->select->error()->hay());

        $this->assertSame($mensajeDeErrorEsperado, $this->select->error()->mensaje());
        $this->assertSame($codigoDeErrorEsperado, $this->select->error()->codigo());
    }

    public function dataValoresInvalidosConSusErrores(): array
    {
        return [
            [
                'opcion inexistente',
                ['unica_opcion_valida'],
                Errores::ERROR_OPCION_INVALIDA,
                TipoSelect::OPCION_INVALIDA
            ],
        ];
    }

}
