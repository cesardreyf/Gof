<?php

declare(strict_types=1);

namespace Test\Sistema\Formulario;

use Gof\Sistema\Formulario\Formulario;
use Gof\Sistema\Formulario\Interfaz\Campo;
use Gof\Sistema\Formulario\Interfaz\Tipos;
use PHPUnit\Framework\TestCase;

class FormularioTest extends TestCase
{

    public function testConfiguracionPorDefecto(): void
    {
        $formulario = new Formulario([]);
        $this->assertSame(Formulario::CONFIGURACION_POR_DEFECTO, $formulario->configuracion()->obtener());
    }

    /**
     * @dataProvider dataDatosDeFormulario
     */
    public function testObtencionDeCamposDesdeArrayDeDatos(string $clave, int $tipo, mixed $valor): void
    {
        $datosDelFormulario = [$clave => $valor];
        $formulario = new Formulario($datosDelFormulario);

        $campo = $formulario->campo($clave, $tipo);
        $this->assertInstanceOf(Campo::class, $campo);

        $this->assertSame($tipo,  $campo->tipo());
        $this->assertSame($clave, $campo->clave());
        $this->assertSame($valor, $campo->valor());
    }

    public function dataDatosDeFormulario(): array
    {
        return [
            ['nombre_del_campo', Tipos::TIPO_STRING, 'valor_del_campo'],
            ['numero_natural', Tipos::TIPO_INT, PHP_INT_MAX],
            ['numero_entero', Tipos::TIPO_INT, PHP_INT_MIN],
        ];
    }

    public function testObtenerListaDeLosErroresOcurridosDuranteLaValidacionDeLosCampos(): void
    {
        $nombreDeUnCampoInexistenteEnLosDatosDelFormulario = 'campo_inexistente';
        $propiedadDeTipoInt = 'error_se_espera_un_int_pero_es_un_string';
        $datosDelFormulario = [$propiedadDeTipoInt => 'abcdef'];

        $formulario = new Formulario($datosDelFormulario);
        $this->assertEmpty($formulario->errores());

        $formulario->campo($nombreDeUnCampoInexistenteEnLosDatosDelFormulario, Tipos::TIPO_STRING);
        $formulario->campo($propiedadDeTipoInt, Tipos::TIPO_INT);
        $this->assertFalse($formulario->validar());

        $arrayDeErrores = $formulario->errores();
        $this->assertTrue(isset($arrayDeErrores[$propiedadDeTipoInt]));
        $this->assertTrue(isset($arrayDeErrores[$nombreDeUnCampoInexistenteEnLosDatosDelFormulario]));
    }

    /**
     * @dataProvider dataDatosYCamposDeunFormularioInvalido
     */
    public function testValidarLosDatosDelFormularioDevuelveFalse(array $datos, array $campos): void
    {
        $formulario = new Formulario($datos);
        $this->assertEmpty($formulario->errores());

        array_walk($campos, function(int $tipo, string $nombre) use ($formulario) {
            $formulario->campo($nombre, $tipo);
        });

        $this->assertFalse($formulario->validar());
        $this->assertNotEmpty($formulario->errores());
    }

    /**
     * @dataProvider dataDatosYCamposDeunFormularioInvalido
     */
    public function testLimpiarErrores(array $datos, array $campos): void
    {
        $formulario = new Formulario($datos);
        array_walk($campos, function(int $tipo, string $nombre) use ($formulario) {
            $formulario->campo($nombre, $tipo);
        });

        $this->assertFalse($formulario->validar());
        $formulario->limpiarErrores();
        $this->assertEmpty($formulario->errores());
    }

    public function dataDatosYCamposDeunFormularioInvalido(): array
    {
        return [
            [
                ['integer' => 'no soy un número entero'],
                ['integer' => Tipos::TIPO_INT]
            ],
        ];
    }

    public function testValidarLosDatosDelFormularioDevuelveTrue(): void
    {
        $datosDelFormulario = ['nombre_de_campo' => PHP_INT_MAX];
        $formulario = new Formulario($datosDelFormulario);
        $formulario->campo('nombre_de_campo', Tipos::TIPO_INT);
        $this->assertTrue($formulario->validar());
        $this->assertEmpty($formulario->errores());
    }

}
