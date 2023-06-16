<?php

declare(strict_types=1);

namespace Test\Sistema\Formulario;

use Gof\Interfaz\Bits\Mascara;
use Gof\Sistema\Formulario\Formulario;
use Gof\Sistema\Formulario\Interfaz\Campo;
use Gof\Sistema\Formulario\Interfaz\Tipos;
use PHPUnit\Framework\TestCase;

class FormularioTest extends TestCase
{

    public function testConfiguracionPorDefecto(): void
    {
        $formulario = new Formulario([]);
        $this->assertInstanceOf(Mascara::class, $formulario->configuracion());
        $this->assertSame(Formulario::CONFIGURACION_POR_DEFECTO, $formulario->configuracion()->obtener());
    }

    /**
     * @dataProvider dataNombreDeCampoYSuTipo
     */
    public function testCrearCampoSegunElTipo(string $nombreDelCampo, int $tipoDeDatoEsperado): void
    {
        $formulario = new Formulario([]);
        $campoCreadoPorElSistema = $formulario->campo($nombreDelCampo, $tipoDeDatoEsperado);

        $this->assertInstanceOf(Campo::class, $campoCreadoPorElSistema);
        $this->assertSame($tipoDeDatoEsperado, $campoCreadoPorElSistema->tipo());
    }

    public function dataNombreDeCampoYSuTipo(): array
    {
        return [
            ['campo_de_tipo_int', Tipos::TIPO_INT],
            ['campo_de_tipo_float', Tipos::TIPO_FLOAT],
        ];
    }

    /**
     * @dataProvider dataNombreDeCampoYSuTipo
     */
    public function testObtenerCampoPreviamenteCreado(string $nombreDelCampo, int $tipoDeDatoEsperado): void
    {
        $formulario = new Formulario([]);
        $campoCreadoPorElSistema = $formulario->campo($nombreDelCampo, $tipoDeDatoEsperado);
        $this->assertSame($campoCreadoPorElSistema, $formulario->campo($nombreDelCampo));
    }

    public function testValidarAlCrearElCampo(): void
    {
        $campoDeTipoInt = 'campo_de_tipo_int_invalido';
        $datosDeFormulario = [$campoDeTipoInt => PHP_FLOAT_MAX];

        // Formulario sin validación al crear el campo
        $formularioSinValidarAlCrear = new Formulario($datosDeFormulario);
        $formularioSinValidarAlCrear->configuracion()->desactivar(Formulario::VALIDAR_AL_CREAR);
        $campoCreadoPorElSistema = $formularioSinValidarAlCrear->campo($campoDeTipoInt, Tipos::TIPO_INT);

        $this->assertFalse($campoCreadoPorElSistema->error()->hay());
        $this->assertEmpty($formularioSinValidarAlCrear->errores());


        // Formulario con validación al crear el campo
        $formularioQueValidaAlCrear = new Formulario($datosDeFormulario);
        $formularioQueValidaAlCrear->configuracion()->activar(Formulario::VALIDAR_AL_CREAR);
        $campoCreadoPorElSistema = $formularioQueValidaAlCrear->campo($campoDeTipoInt, Tipos::TIPO_INT);

        $this->assertTrue($campoCreadoPorElSistema->error()->hay());
        $this->assertNotEmpty($formularioQueValidaAlCrear->errores());
    }

    /**
     * @dataProvider dataDatosDeUnFormularioValidoConCamposYTipos
     */
    public function testValidarDevuelveTrueSoloSiTodosLosCamposSonValidos(array $datosDeFormulario, array $camposYTipos): void
    {
        $formulario = new Formulario($datosDeFormulario);

        foreach( $camposYTipos as $nombreDelCampo => $tipoDeDato ) {
            $formulario->campo($nombreDelCampo, $tipoDeDato);
        }

        $this->assertTrue($formulario->validar());
        $this->assertEmpty($formulario->errores());
    }

    public function dataDatosDeUnFormularioValidoConCamposYTipos(): array
    {
        return [
            [
                ['campo_de_tipo_int' => PHP_INT_MAX],
                ['campo_de_tipo_int' => Tipos::TIPO_INT]
            ],
        ];
    }

    /**
     * @dataProvider dataDatosDeUnFormularioInvalidoConCamposYTipos
     */
    public function testValidarDevuelveFalseSiUnoOMasCamposSonInvalidos(array $datosDeFormulario, array $camposYTipos): void
    {
        $formulario = new Formulario($datosDeFormulario);

        foreach( $camposYTipos as $nombreDelCampo => $tipoDeDato ) {
            $formulario->campo($nombreDelCampo, $tipoDeDato);
        }

        $this->assertFalse($formulario->validar());
        $this->assertNotEmpty($formulario->errores());
    }

    /**
     * @dataProvider dataDatosDeUnFormularioInvalidoConCamposYTipos
     */
    public function testGuardarErroresAsociandoElNombreDelCampo(array $datosDeFormulario, array $camposYTipos): void
    {
        $formulario = new Formulario($datosDeFormulario);

        foreach( $camposYTipos as $nombreDelCampo => $tipoDeDato ) {
            $formulario->campo($nombreDelCampo, $tipoDeDato);
        }

        $this->assertFalse($formulario->validar());
        $this->assertCount(count($camposYTipos), $formulario->errores());
        $this->assertSame(array_keys($camposYTipos), array_keys($formulario->errores()));
    }

    /**
     * @dataProvider dataDatosDeUnFormularioInvalidoConCamposYTipos
     */
    public function testLimpiarErrores(array $datosDeFormulario, array $camposYTipos): void
    {
        $formulario = new Formulario($datosDeFormulario);

        foreach( $camposYTipos as $nombreDelCampo => $tipoDeDato ) {
            $formulario->campo($nombreDelCampo, $tipoDeDato);
        }

        $this->assertFalse($formulario->validar());
        $this->assertNotEmpty($formulario->errores());

        $formulario->limpiarErrores();
        $this->assertEmpty($formulario->errores());
    }

    public function dataDatosDeUnFormularioInvalidoConCamposYTipos(): array
    {
        return [
            [
                [
                    'integer' => PHP_FLOAT_MAX,
                    'float' => PHP_INT_MAX
                ],
                [
                    'integer' => Tipos::TIPO_INT,
                    'float' => Tipos::TIPO_FLOAT
                ]
            ],
        ];
    }

}
