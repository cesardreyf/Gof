<?php

declare(strict_types=1);

namespace Test\Sistema\MVC\Rutas\Simple;

use Exception;
use Gof\Contrato\Enrutador\Enrutador;
use Gof\Gestor\Enrutador\Simple\Enrutador as EnrutadorSimple;
use Gof\Sistema\MVC\Rutas\Simple\Datos;
use Gof\Sistema\MVC\Rutas\Simple\Gestor;
use PHPUnit\Framework\TestCase;

class GestorTest extends TestCase
{
    private ?Enrutador $enrutador;
    private Gestor $gestor;

    public function setUp(): void
    {
        $this->enrutador = null;
        $this->gestor = new Gestor($this->enrutador);
    }

    public function testActivarDevuelveFalseSiNoHayDatos(): void
    {
        $this->assertTrue($this->gestor->datosInvalidos());
        $this->assertFalse($this->gestor->activar());
    }

    public function testActivarLanzaExcepcionSiLosDatosSonInvalidosAlActivarElGestor(): void
    {
        $this->expectException(Exception::class);
        $this->gestor->lanzarExcepcion = true;
        $this->gestor->activar();
    }

    public function testActivarLanzaExcepcionSiLosDatosSonInvalidos(): void
    {
        $this->expectException(Exception::class);
        $this->gestor->lanzarExcepcion = true;
        $this->gestor->datosInvalidos();
    }

    public function dataDatosValidos(): array
    {
        $datosValidos = new Datos();
        $datosValidos->separador = '/';
        $datosValidos->claveGet = 'clave_get';
        $datosValidos->paginaPrincipal = 'index';
        $datosValidos->paginaError404 = 'error404';
        return [[$datosValidos]];
    }

    /**
     * @dataProvider dataDatosValidos
     */
    public function testActivarColocaElEnrutadorSimpleEnElPuntero(Datos $datos): void
    {
        $this->gestor->datos = $datos;
        $this->gestor->lanzarExcepcion = false;

        $this->assertNull($this->enrutador);
        $this->assertTrue($this->gestor->activar());
        $this->assertInstanceOf(EnrutadorSimple::class, $this->enrutador);
    }

}
