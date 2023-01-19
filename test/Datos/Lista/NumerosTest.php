<?php

declare(strict_types=1);

use Gof\Datos\Lista\Numero\ListaDeEnteros;
use Gof\Interfaz\Lista\Enteros;
use PHPUnit\Framework\TestCase;

class NumerosTest extends TestCase
{

    public function test_listaVacia(): Enteros
    {
        $numerosEnteros = new ListaDeEnteros();
        $this->assertEmpty($numerosEnteros->lista());
        return $numerosEnteros;
    }

    /**
     *  @depends test_listaVacia
     */
    public function test_agregarNumerosEnteros(Enteros $numerosEnteros): void
    {
        $numerosEnteros->agregar(1);
        $this->assertSame(1, $numerosEnteros->lista()[0]);

        $numerosEnteros->agregar(-1);
        $this->assertSame(-1, $numerosEnteros->lista()[1]);
    }

    public function test_agregarOtrosTiposDeDatosEnElConstructor(): void
    {
        $this->expectException(Exception::class);
        $numeroEnteros = new ListaDeEnteros([1, 2.3, -4.5, 'seis', [7]]);
    }

}
