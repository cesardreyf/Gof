<?php

declare(strict_types=1);

namespace Test\Datos\Lista;

use Exception;
use Gof\Datos\Lista\Texto\ListaDeTextos;
use Gof\Interfaz\Lista\Textos;
use PHPUnit\Framework\TestCase;

class TextosTest extends TestCase
{

    public function test_listaVaciaAlCrearLaInstancia(): Textos
    {
        $lista = new ListaDeTextos();
        $this->assertEmpty($lista->lista());
        return $lista;
    }

    /**
     *  @depends test_listaVaciaAlCrearLaInstancia
     */
    public function test_agregarTexto(Textos $lista): ListaDeTextos
    {
        $lista->agregar('una cadena');
        $this->assertSame('una cadena',  $lista->lista()[0]);

        $lista->agregar('otra cadena');
        $this->assertSame('una cadena',  $lista->lista()[0]);
        $this->assertSame('otra cadena', $lista->lista()[1]);

        return $lista;
    }

    /**
     *  @depends test_agregarTexto
     */
    public function test_tipoDeDatos(Textos $lista): void
    {
        $lista->agregar('12345678901');
        $lista->agregar('1.234567890');
        $lista->agregar('-1234567890');

        $this->assertContainsOnly('string', $lista->lista());
    }

    public function test_argumentosDelConstructorValidos(): void
    {
        $lista = new ListaDeTextos(['a', 'b']);
        $this->assertSame(['a', 'b'], $lista->lista());

        $lista->agregar('c');
        $this->assertSame(['a', 'b', 'c'], $lista->lista());
    }

    public function test_argumentosDelConstructorInvalidos(): void
    {
        $this->expectException(Exception::class);
        $listaInvalida = new ListaDeTextos(['a', 8, ['c']]);
    }

}
