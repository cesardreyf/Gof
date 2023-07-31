<?php

declare(strict_types=1);

namespace Test\Sistema\MVC\Rut\Inters;

use Gof\Sistema\MVC\Inters\Contenedor\Contenedor;
use PHPUnit\Framework\TestCase;

class ContenedorTest extends TestCase
{
    public int $ptr;
    public int $rutaId;
    public array $rutas;
    public array $inters;
    public Contenedor $contenedor;

    public function setUp(): void
    {
        $this->ptr = 0;
        $this->rutaId = 0;
        $this->rutas = [];
        $this->inters = [];
        $this->contenedor = new Contenedor($this->rutaId, $this->inters, $this->rutas, $this->ptr);
    }

    public function testAgregarYObtenerUnInter(): void
    {
        $nombreDelInter = 'Un\Inter\Cualquiera';
        $this->assertEmpty($this->contenedor->obtener());
        $this->contenedor->agregar($nombreDelInter);
        $listaDeInters = $this->contenedor->obtener();
        $this->assertIsArray($listaDeInters);
        $this->assertNotEmpty($listaDeInters);
        $this->assertSame($nombreDelInter, array_pop($listaDeInters));
    }

    /**
     * @dataProvider dataListaDeInters
     */
    public function testAgregarVariosIntersYObtenerlosTodos(array $listaDeInters): void
    {
        $this->assertEmpty($this->contenedor->obtener());
        foreach( $listaDeInters as $inter ) {
            $this->contenedor->agregar($inter);
        }
        $listaDeIntersObtenidos = $this->contenedor->obtener();
        $this->assertSame($listaDeInters, $listaDeIntersObtenidos);
    }

    /**
     * @dataProvider dataListaDeInters
     */
    public function testAgregarVariosIntersRemoverAlgunosYObtenerLosQueQuedan(array $listaDeInters): void
    {
        $this->assertEmpty($this->contenedor->obtener());
        foreach( $listaDeInters as $inter ) {
            $this->contenedor->agregar($inter);
        }

        $alAzar = rand(0, count($listaDeInters) - 1);
        $this->contenedor->remover($listaDeInters[$alAzar]);
        unset($listaDeInters[$alAzar]);

        $listaDeIntersObtenidos = $this->contenedor->obtener();
        $this->assertEquals(array_values($listaDeInters), array_values($listaDeIntersObtenidos));
    }

    public function dataListaDeInters(): array
    {
        return [[
            [
                'Un\Inter',
                'Otro\Inter',
                'Namespace\Class',
                'Nombre\De\Una\Clase',
            ]
        ]];
    }

}
