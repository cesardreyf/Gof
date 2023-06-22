<?php

declare(strict_types=1);

namespace Test\Sistema\Formulario\Gestor;

use Gof\Datos\Errores\Mensajes\Error;
use Gof\Sistema\Formulario\Datos\Campo;
use Gof\Sistema\Formulario\Gestor\Campos;
use Gof\Sistema\Formulario\Gestor\Sistema;
use Gof\Sistema\Formulario\Interfaz\Campo\Validable;;
use Gof\Sistema\Formulario\Interfaz\Configuracion;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Interfaz\Tipos;
use Gof\Sistema\Formulario\Validar\ValidarExistencia;
use PHPUnit\Framework\TestCase;

class CamposTest extends TestCase
{
    private Campos $campos;
    private Sistema $sistema;

    public function setUp(): void
    {
        $this->sistema = new Sistema();
        $this->campos = new Campos($this->sistema);
    }

    /**
     * @dataProvider dataNombreDeCamposTipoDeDatoYNombreDeLaClaseDelCampo
     */
    public function testCrearCampos(string $nombreDelCampo, int $tipoDeDato, string $nombreDeLaClaseEsperada): void
    {
        $campoCreado = $this->campos->crear($nombreDelCampo, $tipoDeDato);
        $this->assertInstanceOf($nombreDeLaClaseEsperada, $campoCreado);
        $this->assertNull($campoCreado->valor());
    }

    /**
     * @dataProvider dataNombreDeCamposTipoDeDatoYNombreDeLaClaseDelCampo
     */
    public function testCrearCamposAgregaElCampoCreadoAlVectorDeCamposDelSistema(string $nombreDelCampo, int $tipoDeDato, string $nombreDeLaClaseEsperada): void
    {
        $campoCreado = $this->campos->crear($nombreDelCampo, $tipoDeDato);
        $this->assertArrayHasKey($nombreDelCampo, $this->sistema->campos);
    }

    /**
     * @dataProvider dataNombreDeCamposTipoDeDatoYNombreDeLaClaseDelCampo
     */
    public function testCrearCamposInxistentes(string $nombreDelCampo, int $tipoDeDato): void
    {
        $this->assertEmpty($this->sistema->datos);
        $campoCreado = $this->campos->crear($nombreDelCampo, $tipoDeDato);

        $this->assertNull($campoCreado->valor());
        $this->assertTrue($campoCreado->error()->hay());
        $this->assertSame(Errores::ERROR_CAMPO_INEXISTENTE, $campoCreado->error()->codigo());
        $this->assertSame(ValidarExistencia::ERROR_MENSAJE, $campoCreado->error()->mensaje());
    }

    /**
     * @dataProvider dataNombreDeCamposTipoDeDatoYNombreDeLaClaseDelCampo
     */
    public function testCrearCamposActualizaLaCache(string $nombreDelCampo, int $tipoDeDato): void
    {
        $this->sistema->actualizarCache = false;
        $this->campos->crear($nombreDelCampo, $tipoDeDato);
        $this->assertTrue($this->sistema->actualizarCache);
    }

    public function dataNombreDeCamposTipoDeDatoYNombreDeLaClaseDelCampo(): array
    {
        return [
            ['campo_de_tipo_array',  Tipos::TIPO_ARRAY,  Campo\TipoArray::class],
            ['campo_de_tipo_float',  Tipos::TIPO_FLOAT,  Campo\TipoFloat::class],
            ['campo_de_tipo_int',    Tipos::TIPO_INT,    Campo\TipoInt::class],
            ['campo_de_tipo_select', Tipos::TIPO_SELECT, Campo\TipoSelect::class],
            ['campo_de_tipo_string', Tipos::TIPO_STRING, Campo\TipoString::class],
            ['campo_de_tipo_tabla',  Tipos::TIPO_TABLA,  Campo\TipoTabla::class],
        ];
    }

    /**
     * @dataProvider dataNombreDeCamposTipoDeDatoYValorDelCampo
     */
    public function dataEstablecerValorDelCampoSiExiste(string $nombreDelCampo, int $tipoDeCampo, mixed $valorDelCampoEsperado): void
    {
        $this->sistema->datos[$nombreDelCampo] = $valorDelCampoEsperado;

        $campoCreado = $this->campos->crear($nombreDelCampo, $tipoDeCampo);
        $this->assertSame($valorDelCampoEsperado, $campoCreado->valor());
    }

    public function dataNombreDeCamposTipoDeDatoYValorDelCampo(): array
    {
        return [
            ['campo_de_tipo_int', Tipos::TIPO_INT, PHP_INT_MAX],
            ['campo_de_tipo_string', Tipos::TIPO_STRING, 'algo bobo'],
        ];
    }

    // public function testValidarCampoAlCrear(Campo $campoCreado): void
    // {
    // }

    public function testObtenerCampoSegunElNombreDevuelveLaInstanciaAlmacenadaEnElSistema(): void
    {
        $nombreDelCampo = 'campo_ficticio';
        $campoFicticio = $this->createMock(Campo::class);
        $this->sistema->campos[$nombreDelCampo] = $campoFicticio;
        $this->assertSame($campoFicticio, $this->campos->obtener($nombreDelCampo));
    }

    public function testObtenerUnCampoInexistenteDevuelveNull(): void
    {
        $nombreDelCampo = 'campo_ficticio';
        $this->assertArrayNotHasKey($nombreDelCampo, $this->sistema->campos);
        $this->assertNull($this->campos->obtener($nombreDelCampo));
    }

    public function testLaFuncionValidarInvocaElMetodoValidarDeLosCampos(): void
    {
        $this->sistema->campos[] = $this->createMock(Campo::class);;
        $this->sistema->campos[0]
            ->expects($this->once())
            ->method('validar');

        $this->campos->validar();
    }

    public function testValidarSoloDevuelveTrueSiTodosLosCamposSonValidos(): void
    {
        $campoObligatorio1 = $this->createMock(Campo::class);
        $campoObligatorio2 = $this->createMock(Campo::class);
        $campoObligatorio3 = $this->createMock(Campo::class);

        $campoObligatorio1
            ->method('obligatorio')
            ->willReturn(true);

        $campoObligatorio1
            ->expects($this->once())
            ->method('validar')
            ->willReturn(true);

        $campoObligatorio2
            ->method('obligatorio')
            ->willReturn(true);

        $campoObligatorio2
            ->expects($this->once())
            ->method('validar')
            ->willReturn(true);

        $campoObligatorio3
            ->method('obligatorio')
            ->willReturn(true);

        $campoObligatorio3
            ->expects($this->once())
            ->method('validar')
            ->willReturn(false);

        $this->sistema->campos[] = $campoObligatorio1;
        $this->sistema->campos[] = $campoObligatorio2;
        $this->sistema->campos[] = $campoObligatorio3;
        $this->assertFalse($this->campos->validar());
    }

    /**
     * @dataProvider dataCodigosDeErroresQueSonIgnoradosEnCamposOpcionalesAlValidarlos
     */
    public function testValidarDevuelveTrueSiLosCamposObligatoriosSonValidosYLosOpcionalesTienenErroresEsperados(int $codigoDeErrorIgnorado): void
    {
        $campoObligatorio = $this->createMock(Campo::class);
        $campoOpcional = $this->createMock(Campo::class);
        $error = $this->createMock(Error::class);

        $campoObligatorio
            ->method('obligatorio')
            ->willReturn(true);

        $campoObligatorio
            ->method('validar')
            ->willReturn(true);

        $campoOpcional
            ->method('obligatorio')
            ->willReturn(false);

        $campoOpcional
            ->method('validar')
            ->willReturn(false);

        $campoOpcional
            ->method('error')
            ->willReturn($error);

        $error
            ->method('hay')
            ->willReturn(true);

        $error
            ->expects($this->once())
            ->method('codigo')
            ->willReturn($codigoDeErrorIgnorado);

        $this->sistema->campos[] = $campoObligatorio;
        $this->sistema->campos[] = $campoOpcional;
        $this->assertTrue($this->campos->validar());
    }

    /**
     * @dataProvider dataCodigosDeErroresQueSonIgnoradosEnCamposOpcionalesAlValidarlos
     */
    public function testLimpiarCamposOpcionalesAlValidar(int $codigoDeError): void
    {
        $error = $this->createMock(Error::class);
        $campo = $this->createMock(Campo::class);

        $error
            ->expects($this->once())
            ->method('codigo')
            ->willReturn($codigoDeError);

        $campo
            ->method('error')
            ->willReturn($error);

        $campo
            ->expects($this->once())
            ->method('validar')
            ->willReturn(false);

        $campo
            ->expects($this->once())
            ->method('obligatorio')
            ->willReturn(false);

        $error
            ->expects($this->once())
            ->method('limpiar');

        $this->sistema->campos[] = $campo;
        $this->sistema->configuracion->activar(Configuracion::LIMPIAR_ERRORES_CAMPOS_OPCIONALES);
        $this->assertTrue($this->campos->validar());
    }

    public function dataCodigosDeErroresQueSonIgnoradosEnCamposOpcionalesAlValidarlos(): array
    {
        return [
            [Errores::ERROR_CAMPO_INEXISTENTE],
            [Errores::ERROR_CAMPO_VACIO],
        ];
    }

    /**
     * @dataProvider dataCampoObligatorioConError
     */
    public function testValidarActualizaLaCacheSoloSiHayCamposInvalidos(array $camposConErrores): void
    {
        $this->sistema->actualizarCache = false;
        $this->sistema->campos = $camposConErrores;
        $this->assertFalse($this->campos->validar());
        $this->assertTrue($this->sistema->actualizarCache);
    }

    public function dataCampoObligatorioConError(): array
    {
        $campo = $this->createMock(Campo::class);
        $campo->method('validar')->willReturn(false);
        $campo->method('obligatorio')->willReturn(true);
        return [[[$campo]]];
    }

    public function testMetodoListaReflejaLaListaDeCamposDelSistema(): void
    {
        $this->sistema->campos[] = $this->createMock(Campo::class);
        $this->assertSame($this->sistema->campos, $this->campos->lista());
    }

    public function testMetodoLimpiarLimpiaLaListaDeCamposYActualizaLaCache(): void
    {
        $this->sistema->campos[] = $this->createMock(Campo::class);
        $this->sistema->actualizarCache = false;
        $this->campos->limpiar();

        $this->assertEmpty($this->sistema->campos);
        $this->assertEmpty($this->campos->lista());
        $this->assertTrue($this->sistema->actualizarCache);
    }

    public function testValidarCamposSinErroresRecorreListaDeValidacionesExtras(): void
    {
        $campoConValidacionExtra = $this->createMock(Campo::class);
        $this->sistema->campos[] = $campoConValidacionExtra;

        $validacionExtra = $this->createMock(Validable::class);
        $validacionExtra
            ->expects($this->once())
            ->method('validar')
            ->willReturn(true);

        $campoConValidacionExtra
            ->method('validar')
            ->willReturn(true);

        $campoConValidacionExtra
            ->method('vextra')
            ->willReturn([$validacionExtra]);

        $this->assertTrue($this->campos->validar());
    }

    public function testValidarCamposConErroresNoRecorreListaDeValidacionesExtras(): void
    {
        $campoConValidacionExtra = $this->createMock(Campo::class);
        $this->sistema->campos[] = $campoConValidacionExtra;

        $validacionExtra = $this->createMock(Validable::class);
        $validacionExtra
            ->expects($this->never())
            ->method('validar');

        $campoConValidacionExtra
            ->method('validar')
            ->willReturn(false);

        $campoConValidacionExtra
            ->method('vextra')
            ->willReturn([$validacionExtra]);

        $this->assertFalse($this->campos->validar());
    }

}
