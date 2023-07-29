<?php

declare(strict_types=1);

namespace Test\Sistema\MVC\Rut\Inters;

use Gof\Sistema\MVC\Rut\Inters\Subgestor;
use PHPUnit\Framework\TestCase;

class SubgestorTest extends TestCase
{
    public int $ptr;
    public int $rutaId;
    public array $rutas;
    public array $inters;
    public Subgestor $subgestor;

    public function setUp(): void
    {
        $this->ptr = 0;
        $this->rutaId = 0;
        $this->rutas = [];
        $this->inters = [];
        $this->subgestor = new Subgestor($this->rutaId, $this->inters, $this->rutas, $this->ptr);
    }

    public function testAgregarYObtenerUnInter(): void
    {
        $nombreDelInter = 'Un\Inter\Cualquiera';
        $this->assertEmpty($this->subgestor->obtener());
        $this->subgestor->agregar($nombreDelInter);
        $listaDeInters = $this->subgestor->obtener();
        $this->assertIsArray($listaDeInters);
        $this->assertNotEmpty($listaDeInters);
        $this->assertSame($nombreDelInter, array_pop($listaDeInters));
    }

    /**
     * @dataProvider dataListaDeInters
     */
    public function testAgregarVariosIntersYObtenerlosTodos(array $listaDeInters): void
    {
        $this->assertEmpty($this->subgestor->obtener());
        foreach( $listaDeInters as $inter ) {
            $this->subgestor->agregar($inter);
        }
        $listaDeIntersObtenidos = $this->subgestor->obtener();
        $this->assertSame($listaDeInters, $listaDeIntersObtenidos);
    }

    /**
     * @dataProvider dataListaDeInters
     */
    public function testAgregarVariosIntersRemoverAlgunosYObtenerLosQueQuedan(array $listaDeInters): void
    {
        $this->assertEmpty($this->subgestor->obtener());
        foreach( $listaDeInters as $inter ) {
            $this->subgestor->agregar($inter);
        }

        $alAzar = rand(0, count($listaDeInters) - 1);
        $this->subgestor->remover($listaDeInters[$alAzar]);
        unset($listaDeInters[$alAzar]);

        $listaDeIntersObtenidos = $this->subgestor->obtener();
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
