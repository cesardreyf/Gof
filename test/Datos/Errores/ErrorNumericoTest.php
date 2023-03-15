<?php

declare(strict_types=1);

namespace Test\Datos\Errores;

use Gof\Datos\Errores\ErrorNumerico;
use Gof\Interfaz\Errores\Errores;
use PHPUnit\Framework\TestCase;

class ErrorNumericoTest extends TestCase
{
    private $errores;

    public function setUp(): void
    {
        $this->errores = new ErrorNumerico();
    }

    public function testImplementarLaInterfazErrores(): void
    {
        $this->assertInstanceOf(Errores::class, $this->errores);
    }

    public function testMetodoHayDevuelveFalseAlInstanciar(): void
    {
        $this->assertFalse($this->errores->hay());
    }

    public function testMetodoErroresDevuelveUnArrayVacioAlInstanciar(): void
    {
        $this->assertEmpty($this->errores->lista());
    }

    /**
     *  @dataProvider dataUnSoloError
     */
    public function testAgregarUnErrorYObtenerlo(int $error): void
    {
        $this->assertSame($error, $this->errores->agregar($error));
        $this->assertNotEmpty($this->errores->lista());
        $this->assertTrue($this->errores->hay());

        $this->assertSame($error, $this->errores->error());
        $this->assertEmpty($this->errores->lista());
        $this->assertFalse($this->errores->hay());
    }

    /**
     *  @dataProvider dataVariosErrores
     */
    public function testAgregarVariosErroresYObtenerlos(array $errores): void
    {
        foreach( $errores as $error ) {
            $this->assertSame($error, $this->errores->agregar($error));
        }

        $this->assertSame($errores, $this->errores->lista());
        $this->assertTrue($this->errores->hay());

        // Organización mundial de los
        // No A Los Bucles Infinitos
        $NALBI = count($errores);

        // Mientras hayan errores y
        // los NALBI no lo boicoteen
        while( $this->errores->hay() && $NALBI-- ) {
            $this->assertSame(array_pop($errores), $this->errores->error());
        }

        $this->assertEmpty($errores);
        $this->assertFalse($this->errores->hay());
        $this->assertEmpty($this->errores->lista());
    }

    public function dataUnSoloError(): array
    {
        return [
            [123456789],
            [-123456789],
            [0]
        ];
    }

    public function dataVariosErrores(): array
    {
        return [
            [[0, 123, 456, 789]],
            [[-123, -456, -789]],
            [[0, 123, -456, 789]]
        ];
    }

}
