<?php

declare(strict_types=1);

use Gof\Gestor\Autoload\Filtro\PSR4;
use Gof\Gestor\Autoload\Interfaz\Filtro;
use PHPUnit\Framework\TestCase;

class PSR4Test extends TestCase
{
    private $filtro;

    public function setUp(): void
    {
        $this->filtro = new PSR4();
    }

    public function testImplementarCorrectamenteLaInterfazDelFiltro(): void
    {
        $this->assertInstanceOf(Filtro::class, $this->filtro);
    }

    /**
     *  @dataProvider dataNombreDeClasesInterfacesYTraitsValidos
     */
    public function testNombreDeClasesInterfazYTraitsValidos(string $nombre): void
    {
        $this->assertTrue($this->filtro->clase($nombre));
    }

    /**
     *  @dataProvider dataNombreDeEspaciosDeNombresValidos
     *  @dataProvider dataEspacioDeNombreVacioValidoParaLosGlobales
     */
    public function testNombresDeEspaciosDeNombresValidos(string $nombre): void
    {
        $this->assertTrue($this->filtro->espacioDeNombre($nombre));
    }

    /**
     *  @dataProvider dataNombreDeClasesInterfacesYTraitsQueNoSeConsideranValidos
     */
    public function testNombreDeClasesInterfazYTraitsValidosQueNoSonValidos(string $nombre): void
    {
        $this->assertFalse($this->filtro->clase($nombre));
    }

    /**
     *  @dataProvider dataNombreDeEspaciosDeNombresNoValidos
     */
    public function testNombresDeEspaciosDeNombresNoValidos(string $nombre): void
    {
        $this->assertFalse($this->filtro->espacioDeNombre($nombre));
    }

    public function dataNombreDeClasesInterfacesYTraitsValidos(): array
    {
        return [
            ['Nombre'],
            ['\SoyValido'],
            ['Se\Permiten_Estas\Lineas_'],
            ['\Soy\Un\Espacio\De\Nombre\Valido'],
            ['\\Yo\\Tambien\\Soy\\Un\\Espacio\\De\\Nombre\\Valido'],
            ['\Yo\\Soy\Raro\\Pero\Tambien\\Sirvo\Como\\Nombre\Valido']
        ];
    }

    public function dataNombreDeClasesInterfacesYTraitsQueNoSeConsideranValidos(): array
    {
        return [
            [''],
            [' '],
            ['1nvalidoPorEmpezarPorUnNumero'],
            ['_No_Deberia_Empezar_Con_Esto'],
            ['ÁcentuadaLasPalabrasProhíbidasEstán_dijoYoga'],
            ['CasiVálido'],
            ['.Los.Puntos.No.Son.Validos'],
            ['\Una\Barra\Al\Final\No\Se\Permite\\'],
            ['Muchas\\\\Barras\\\\En\\\\Medio'],
            ['\\\\Muchas\\\\Barras\\\\Al\\\\Principio'],
            ['Los\Nombres\No\Pueden\Empezar\4si']
        ];
    }

    public function dataEspacioDeNombreVacioValidoParaLosGlobales(): array
    {
        return [['']];
    }

    public function dataNombreDeEspaciosDeNombresValidos(): array
    {
        return [
            ['Namespace'],
            ['espacioDeNombre'],
            ['Un_Solo_Namespace'],
            ['Un3sp4c10D3N0mbr3']
        ];
    }

    public function dataNombreDeEspaciosDeNombresNoValidos(): array
    {
        return [
            ['1nvalido'],
            ['También'],
            ['álgo'],
            [' '],
            ['SinBarrasAlFinal\\'],
            ['Dos\Namespaces']
        ];
    }

}
