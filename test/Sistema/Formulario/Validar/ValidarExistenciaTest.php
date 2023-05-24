<?php

declare(strict_types=1);

namespace Test\Sistema\Formulario\Validar;

use Gof\Datos\Formulario\Campo;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Validar\ValidarExistencia;
use PHPUnit\Framework\TestCase;

class ValidarExistenciaTest extends TestCase
{

    public function testValidarCamposInexistentes(): void
    {
        $datosVacios = [];
        $campo = new Campo('campo_inexistente', 0);
        $validar = new ValidarExistencia($campo, $datosVacios);

        $this->assertFalse($validar->existe());
        $this->assertSame(Errores::ERROR_CAMPO_INEXISTENTE, $campo->error()->codigo());
        $this->assertSame(ValidarExistencia::ERROR_MENSAJE, $campo->error()->mensaje());
    }

    /**
     * @dataProvider dataDatosDelMundial
     */
    public function testValidarCamposExistentes(array $datos): void
    {
        foreach( $datos as $clave => $valor ) {
            $campo = new Campo($clave, 0);
            $validar = new ValidarExistencia($campo, $datos);

            $this->assertTrue($validar->existe());
            $this->assertFalse($campo->error()->hay());
        }
    }

    public function dataDatosDelMundial(): array
    {
        return [
            [[
                'Argentina' => 1,
                'Francia' => 2,
            ]]
        ];
    }

}
