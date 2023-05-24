<?php

declare(strict_types=1);

namespace Test\Sistema\Formulario;

use Gof\Interfaz\Formulario\Campo;
use Gof\Sistema\Formulario\Formulario;
use Gof\Sistema\Formulario\Interfaz\Tipos;
use PHPUnit\Framework\TestCase;

class FormularioTest extends TestCase
{

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
            ['numero_entero', Tipos::TIPO_INT, ~PHP_INT_MAX],
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

        $arrayDeErrores = $formulario->errores();
        $this->assertTrue(isset($arrayDeErrores[$propiedadDeTipoInt]));
        $this->assertTrue(isset($arrayDeErrores[$nombreDeUnCampoInexistenteEnLosDatosDelFormulario]));
    }

}
