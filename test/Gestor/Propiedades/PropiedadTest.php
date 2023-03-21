<?php

declare(strict_types=1);

namespace Test\Gestor\Propiedades;

use Gof\Datos\Lista\Datos\ListaDeDatos;
use Gof\Gestor\Propiedades\Propiedad;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;

interface Adorable
{
    public function adorar();
}

class PropiedadTest extends TestCase
{

    public function testClaseExtiendeDeListaDeDatos(): void
    {
        $propiedad = new Propiedad(Adorable::class);
        $this->assertInstanceOf(ListaDeDatos::class, $propiedad);
    }

    public function testErrorAlPasarUnaInterfazInexistente(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $propiedad = new Propiedad('InterfazInexistente');
    }

    public function testAgregandoCorrectamentePropiedadesDelTipoEsperado(): Propiedad
    {
        $propiedad = new Propiedad(Adorable::class);

        $nombreDelGato = 'michi';
        $gatito = $this->createMock(Adorable::class);

        $resultado = $propiedad->agregar($gatito, $nombreDelGato);
        $this->assertSame($nombreDelGato, $resultado);
        $this->assertNotNull($resultado);

        return $propiedad;
    }

    /**
     * @depends testAgregandoCorrectamentePropiedadesDelTipoEsperado
     */
    public function testObtenerLaPropiedadDelTipoEsperado(Propiedad $propiedad): void
    {
        foreach( $propiedad->lista() as $identificador => $propiedad ) {
            $this->assertInstanceOf(Adorable::class, $propiedad);
        }
    }

    public function testCambiarUnaPropiedadCorrectamente(): void
    {
        $perrito = $this->createMock(Adorable::class);
        $gatito = $this->createMock(Adorable::class);
        $propiedad = new Propiedad(Adorable::class);

        $nombreDelPerro = 'Adolf'; // Adolfo
        $nombreDelGato = 'Hiter'; // Golpeador

        $this->assertSame($nombreDelPerro, $propiedad->agregar($perrito, $nombreDelPerro));
        $this->assertTrue($propiedad->cambiar($nombreDelPerro, $gatito));

        $this->assertNotSame($perrito, $propiedad->obtener($nombreDelPerro));
    }

    public function testExcepcionAlAgregarPropiedadInvalida(): void
    {
        $propiedad = new Propiedad(Adorable::class);
        $this->expectException(InvalidArgumentException::class);

        $propiedadInvalida = new stdClass();
        $propiedad->agregar($propiedadInvalida);
    }

    public function testExcepcionAlCambiarPropiedadValidaPorUnaInvalida(): void
    {
        $propiedad = new Propiedad(Adorable::class);
        $gatito = $this->createMock(Adorable::class);

        $propiedadInvalida = new stdClass();
        $identificador = $propiedad->agregar($gatito);

        $this->expectException(InvalidArgumentException::class);
        $propiedad->cambiar($identificador, $propiedadInvalida);
    }

    public function testIgnorarErrorAlAgregarPropiedadInvalida(): void
    {
        $propiedad = new Propiedad(Adorable::class);
        $propiedad->configuracion()->activar(Propiedad::IGNORAR_PROPIEDADES_INVALIDAS);

        $propiedadInvalida = new stdClass();
        $this->assertNull($propiedad->agregar($propiedadInvalida));
    }

    public function testIgnorarErrorAlCambiarUnaPropiedadValidaPorUnaInvalida(): void
    {
        $gatito = $this->createMock(Adorable::class);
        $propiedad = new Propiedad(Adorable::class);
        $identificador = $propiedad->agregar($gatito);

        $propiedadInvalida = new stdClass();
        $propiedad->configuracion()->activar(Propiedad::IGNORAR_PROPIEDADES_INVALIDAS);
        $this->assertFalse($propiedad->cambiar($identificador, $propiedadInvalida));
        $this->assertSame($gatito, $propiedad->obtener($identificador));
    }

}
