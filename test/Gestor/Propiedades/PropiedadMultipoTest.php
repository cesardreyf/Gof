<?php

declare(strict_types=1);

namespace Test\Gestor\Propiedades;

use Gof\Datos\Lista\Datos\ListaDeDatos;
use Gof\Gestor\Propiedades\PropiedadMultipo;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

interface A {}
interface B {}
interface C {}

class PropiedadMultipoTest extends TestCase
{

    public function testExtiendeDeListaDeDatos(): void
    {
        $propiedad = new PropiedadMultipo(A::class);
        $this->assertInstanceOf(ListaDeDatos::class, $propiedad);
    }

    /**
     * @dataProvider dataListaDeDiferentesTiposDeDatosBasicos
     * @dataProvider dataListaDeObjetosQueNoImplementanLaInterfazA
     */
    public function testAgregarObjetosQueNoImplementenLasInterfacesLanzanUnaExcepcion(mixed $objeto, string $clave): void
    {
        $this->expectException(InvalidArgumentException::class);
        $propiedad = new PropiedadMultipo(A::class);
        $propiedad->agregar($objeto, $clave);
    }

    /**
     * @dataProvider dataListaDeDiferentesTiposDeDatosBasicos
     * @dataProvider dataListaDeObjetosQueNoImplementanLaInterfazA
     */
    public function testAgregarObjetosQueNoImplementenLasInterfacesDevuelveNullSegunConfiguracion(mixed $objeto, string $clave): void
    {
        $propiedad = new PropiedadMultipo(A::class);
        $propiedad->configuracion()->activar(PropiedadMultipo::IGNORAR_PROPIEDADES_INVALIDAS);
        $this->assertNull($propiedad->agregar($objeto, $clave));
    }

    /**
     * @dataProvider dataListaDeDiferentesTiposDeDatosBasicos
     * @dataProvider dataListaDeObjetosQueNoImplementanLaInterfazA
     */
    public function testCambiarObjetosQueNoImplementenLasInterfacesLanzanUnaExcepcion(mixed $objeto, string $clave): void
    {
        $this->expectException(InvalidArgumentException::class);
        $propiedad = new PropiedadMultipo(A::class);
        $propiedad->agregar(new class implements A {}, $clave);
        $propiedad->cambiar($clave, $objeto);
    }

    /**
     * @dataProvider dataListaDeDiferentesTiposDeDatosBasicos
     * @dataProvider dataListaDeObjetosQueNoImplementanLaInterfazA
     */
    public function testCambiarObjetosQueNoImplementenLasInterfacesDevuelveNullSegunConfiguracion(mixed $objeto, string $clave): void
    {
        $propiedad = new PropiedadMultipo(A::class);
        $propiedad->configuracion()->activar(PropiedadMultipo::IGNORAR_PROPIEDADES_INVALIDAS);
        $this->assertNotNull($propiedad->agregar(new class implements A {}, $clave));
        $this->assertFalse($propiedad->cambiar($clave, $objeto));
    }

    public function dataListaDeObjetosQueNoImplementanLaInterfazA(): array
    {
        return [
            [new class implements B {}, 'object_implements_B'],
            [new class implements C {}, 'object_implements_C'],
        ];
    }

    public function dataListaDeDiferentesTiposDeDatosBasicos(): array
    {
        return [
            [true, 'bool'],
            [null, 'null'],
            ['algo', 'string'],
            [3.14159, 'float'],
            [12345, 'integer'],
        ];
    }

    /**
     * @dataProvider dataObjetosValidos
     */
    public function testAgregarYCambiarCorrectamente(object $objeto, ...$interfaces): void
    {
        $propiedad = new PropiedadMultipo(...$interfaces);
        $identificador = $propiedad->agregar($objeto);

        $this->assertIsString($identificador);
        $this->assertTrue($propiedad->cambiar($identificador, clone $objeto));
    }

    public function dataObjetosValidos(): array
    {
        return [
            [new class implements A {},       A::class],
            [new class implements A, B {},    A::class, B::class],
            [new class implements A, B, C {}, A::class, B::class, C::class],
        ];
    }

}
