<?php

declare(strict_types=1);

namespace Test\Datos;

use Gof\Datos\Lista\Lista;
use PHPUnit\Framework\TestCase;

class ListaTest extends TestCase
{

    public function test_listaVacia(): void
    {
        $lista = new Lista([]);
        $this->assertEmpty($lista->lista());
    }

    public function test_listaPorArgumento(): void
    {
        $lista = new Lista(['algo', 'cosa']);
        $this->assertSame(['algo', 'cosa'], $lista->lista());
    }

}
