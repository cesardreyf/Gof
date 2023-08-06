<?php

declare(strict_types=1);

namespace Test\Gestor\Dependencias\Simple;

use Gof\Gestor\Dependencias\Simple\Dependencias;
use Gof\Gestor\Dependencias\Simple\DependenciasMagicas;
use Gof\Gestor\Dependencias\Simple\Excepcion\MetodoInexistente;
use Gof\Gestor\Dependencias\Simple\Excepcion\MetodoReservado;
use PHPUnit\Framework\TestCase;

class Ignorame {}

class DependenciasMagicasTest extends TestCase
{
    private $dependencias;

    public function setUp(): void
    {
        $this->dependencias = new DependenciasMagicas();
    }

    public function testExtenderDeDependencias(): void
    {
        $this->assertInstanceOf(Dependencias::class, $this->dependencias);
    }

    public function testMetodosReservadosVaciosAlInstanciar(): void
    {
        $this->assertEmpty($this->dependencias->metodosReservados());
    }

    public function testAsociarMetodoYObtenerlo(): void
    {
        $objeto = $this->createMock(Ignorame::class);
        $this->dependencias->agregar(Ignorame::class, function() use ($objeto) {
            return $objeto;
        });

        $nombreDelMetodo = 'ignorame';
        $this->dependencias->asociarMetodo($nombreDelMetodo, Ignorame::class);
        $this->assertSame($objeto, $this->dependencias->$nombreDelMetodo());

        $listaDeMetodosReservados = $this->dependencias->metodosReservados();
        $this->assertTrue(in_array(Ignorame::class, $listaDeMetodosReservados));
        $this->assertTrue(in_array($nombreDelMetodo, array_keys($listaDeMetodosReservados)));
    }

    public function testLanzarExcepcionAlAccederAMetodosInexistentes(): void
    {
        $this->expectException(MetodoInexistente::class);
        $this->dependencias->unMetodoInexistente();
    }

    /**
     * @dataProvider dataNombreDeLosMetodosPublicosDelGestorDeDependencias
     */
    public function testLanzarExcepcionAlReservarUnMetodoYaExistenteEnLaClase(string $metodo): void
    {
        $this->expectException(MetodoReservado::class);
        $this->assertFalse($this->dependencias->asociarMetodo($metodo, Ignorame::class));
        $this->dependencias->configuracion()->activar(DependenciasMagicas::LANZAR_EXCEPCION);
        $this->dependencias->asociarMetodo($metodo, Ignorame::class);
    }

    public function dataNombreDeLosMetodosPublicosDelGestorDeDependencias(): array
    {
        return [get_class_methods(DependenciasMagicas::class)];
    }

    public function testRemoverMetodoAlRemoverLaDependencia(): void
    {
        $this->dependencias->configuracion()->activar(
            Dependencias::PERMITIR_REMOVER,
            Dependencias::LANZAR_EXCEPCION
        );
        $resultadoDeAgregar = $this->dependencias->agregar(Ignorame::class, function() {
            return new Ignorame();
        });
        $nombreDelMetodo = 'ignorame';
        $this->assertTrue($resultadoDeAgregar);
        $this->assertTrue($this->dependencias->asociarMetodo($nombreDelMetodo, Ignorame::class));
        $this->assertInstanceOf(Ignorame::class, $this->dependencias->$nombreDelMetodo());
        $this->assertCount(1, $this->dependencias->metodosReservados());
        $this->assertTrue($this->dependencias->remover(Ignorame::class));
        $this->assertEmpty($this->dependencias->metodosReservados());
        $this->expectException(MetodoInexistente::class);
        $this->dependencias->$nombreDelMetodo();
    }

}
