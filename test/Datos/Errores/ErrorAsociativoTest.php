<?php

declare(strict_types=1);

namespace Test\Datos\Errores;

use Gof\Datos\Errores\ErrorAsociativo;
use PHPUnit\Framework\TestCase;

class ErrorAsociativoTest extends TestCase
{

    public function testListaDeErroresAsociativosVaciosAlInstanciarElObjeto(): void
    {
        $errores = new ErrorAsociativo();
        $this->assertEmpty($errores->lista());
    }

    /**
     * @dataProvider dataErroresNumericos
     */
    public function testAgregarErrorYObtenerlo(string $identificador, int $error): void
    {
        $errores = new ErrorAsociativo();
        $this->assertEmpty($errores->lista());

        $this->assertSame($error, $errores->agregar($error, $identificador));
        $this->assertNotEmpty($errores->lista());

        $this->assertSame($error, $errores->error());
        $this->assertEmpty($errores->lista());
    }

    public function dataErroresNumericos(): array
    {
        return [
            ['unidad', 1], ['decenas', 20], ['centenas', 300], ['miles', 4000], ['decenas_de_miles', 50000]
        ];
    }

    /**
     * @dataProvider dataVectorDeErroresConClavesAsociadas
     */
    public function testAgregarErroresConClavesAsociadasPorConstructor(array $vectorDeErrores): void
    {
        $errores = new ErrorAsociativo($vectorDeErrores);
        $this->assertSame($vectorDeErrores, $errores->lista());
    }

    /**
     * @dataProvider dataVectorDeErroresConClavesAsociadas
     */
    public function testAgregarErroresConClavesAsociadas(array $vectorDeErrores): void
    {
        $errores = new ErrorAsociativo();
        foreach( $vectorDeErrores as $identificador => $error ) {
            $this->assertSame($error, $errores->agregar($error, $identificador));
        }

        $this->assertSame($vectorDeErrores, $errores->lista());
    }

    public function dataVectorDeErroresConClavesAsociadas(): array
    {
        return [
            [['error_de_usuario' => 123, 'error_en_el_modulo_a' => 456, 'sistema' => 789, 'otro' => 0]]
        ];
    }

}
