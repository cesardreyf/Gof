<?php

declare(strict_types=1);

namespace Test\Gestor\Formulario\Datos;

use Gof\Gestor\Formulario\Datos\DatosSimples;
use Gof\Interfaz\Bits\Mascara;
use Gof\Interfaz\Errores\Errores;
use PHPUnit\Framework\TestCase;

class DatosSimplesTest extends TestCase
{
    const SIN_CONFIGURACION = 0;

    public function testDatosVacios(): void
    {
        $form = new DatosSimples([]);
        $this->assertTrue($form->vacio());

        $form = new DatosSimples(['algo']);
        $this->assertFalse($form->vacio());
    }

    public function testMetodoErroresDevuelveUnaInstanciaDeErrores(): void
    {
        $form = new DatosSimples([]);
        $this->assertInstanceOf(Errores::class, $form->errores());
    }

    public function testErroresConClavesAsociadasALasClavesDeLosDatos(): void
    {
        $datosQueNoSonEnteros = ['a' => 'algo', 'b' => 'bolsa', 'c' => 'casa'];
        $form = new DatosSimples($datosQueNoSonEnteros);

        foreach( $datosQueNoSonEnteros as $clave => $valor ) {
            $this->assertNull($form->entero($clave));
        }

        $listaDeErrores = $form->errores()->errores();
        $clavesDeLosErrores = array_keys($listaDeErrores);
        $clavesDeLosDatos = array_keys($datosQueNoSonEnteros);
        $this->assertSame($clavesDeLosErrores, $clavesDeLosDatos);
    }

    public function testMetodoConfiguracionDevuelveUnaInstanciaDeMascaraDeBits(): void
    {
        $form = new DatosSimples([]);
        $this->assertInstanceOf(Mascara::class, $form->configuracion());
    }

    public function testConfiguracionPorDefecto(): void
    {
        $form = new DatosSimples([]);
        $configuracionPorDefecto = DatosSimples::INTERPRETAR_CADENAS | DatosSimples::INTERPRETAR_BOOLEANO_VACIO;
        $this->assertSame($configuracionPorDefecto, $form->configuracion()->obtener());
    }

    public function testMetodoExisteDevuelveTrue(): void
    {
        $datos = ['existente' => 'algo'];
        $form = new DatosSimples($datos);

        $this->assertTrue($form->existe('existente'));
        $this->assertFalse($form->existe('inexistente'));
        $this->assertFalse($form->errores()->hay());
    }

    /**
     * @dataProvider dataArrayDeCadenasSinClavesAsociadas
     */
    public function testObtenerCadenasDeUnArraySinClavesAsociadas(array $datos): void
    {
        $form = new DatosSimples($datos);

        for($i = 0, $j = count($datos); $i < $j; $i++) {
            $resultado = $form->cadena((string)$i);

            $this->assertIsString($resultado);
            $this->assertSame($datos[$i], $resultado);
        }

        $this->assertFalse($form->errores()->hay());
    }

    public function dataArrayDeCadenasSinClavesAsociadas(): array
    {
        return [
            [['algo', 'bordó', 'cagüasú', '1234567890', '-1.098765432']],
        ];
    }

    /**
     * @dataProvider dataCadenasSinErroresConClavesAsociativas
     */
    public function testObtenerCadenasConClavesAsociadasSinErrores($clave, $valor): void
    {
        $form = new DatosSimples([$clave => $valor]);
        $this->assertSame($valor, $form->cadena($clave));
        $this->assertFalse($form->errores()->hay());
    }

    public function dataCadenasSinErroresConClavesAsociativas(): array
    {
        return [
            ['clave', 'valor'],
            ['no_interpretar_un_cero_como_campo_vacio', '0']
        ];
    }

    /**
     * @dataProvider dataClavesValoresDeCadenasConErroresDeFormato
     */
    public function testErrorAlObtenerCadenas($clave, $valor, int $errorEsperado): void
    {
        $form = new DatosSimples([$clave => $valor]);
        $this->assertNull($form->cadena($clave));
        $this->assertTrue($form->errores()->hay());
        $this->assertSame($errorEsperado, $form->errores()->error());
    }

    public function dataClavesValoresDeCadenasConErroresDeFormato(): array
    {
        return [
            ['array',                                             [],              DatosSimples::FORMATO_INCORRECTO_STRING],
            ['cadena_como_tal_pero_vacia',                        '',              DatosSimples::CAMPO_VACIO],
            ['bool_true',                                         true,            DatosSimples::FORMATO_INCORRECTO_STRING],
            ['bool_false',                                        false,           DatosSimples::FORMATO_INCORRECTO_STRING],
            ['objeto',                                            new \stdClass(), DatosSimples::FORMATO_INCORRECTO_STRING],
            ['numero_entero_positivo',                            12345,           DatosSimples::FORMATO_INCORRECTO_STRING],
            ['numero_entero_negativo',                            -6789,           DatosSimples::FORMATO_INCORRECTO_STRING],
            ['numero_flotante_positivo',                          1.2345,          DatosSimples::FORMATO_INCORRECTO_STRING],
            ['numero_flotante_negativo',                          -6.7890,         DatosSimples::FORMATO_INCORRECTO_STRING],
            ['valor_nulo_es_interpretado_como_campo_inexistente', null,            DatosSimples::CAMPO_INEXISTENTE]
        ];
    }

    /**
     * @dataProvider dataNumerosEnterosValidos
     */
    public function testObtenerNumerosEnteros(string $clave, $valor, $configuracion): void
    {
        $form = new DatosSimples([$clave => $valor], $configuracion);
        $resultado = $form->entero($clave);

        $this->assertIsInt($resultado);
        $this->assertEquals($valor, $resultado);
        $this->assertFalse($form->errores()->hay());
    }

    public function dataNumerosEnterosValidos(): array
    {
        return [
            ['int_entero_positivo',    12345,    self::SIN_CONFIGURACION],
            ['int_entero_negativo',    -67890,   self::SIN_CONFIGURACION],
            ['string_entero_positivo', '12345',  DatosSimples::INTERPRETAR_CADENAS],
            ['string_entero_negativo', '-67890', DatosSimples::INTERPRETAR_CADENAS]
        ];
    }

    /**
     + @dataProvider dataNumerosEnterosInvalidosConSusErrores
     */
    public function testErrorAlObtenerUnNumeroEntero(string $clave, $valor, int $errorEsperado, int $configuracion): void
    {
        $form = new DatosSimples([$clave => $valor], $configuracion);
        $this->assertNull($form->entero($clave));
        $this->assertSame($errorEsperado, $form->errores()->error());
        
    }

    public function dataNumerosEnterosInvalidosConSusErrores(): array
    {
        return [
            ['objeto',                     new \stdClass(), DatosSimples::FORMATO_INCORRECTO_INT, self::SIN_CONFIGURACION],
            ['un_array',                   [],       DatosSimples::FORMATO_INCORRECTO_INT, self::SIN_CONFIGURACION],
            ['bool_true',                  true,     DatosSimples::FORMATO_INCORRECTO_INT, self::SIN_CONFIGURACION],
            ['bool_false',                 false,    DatosSimples::FORMATO_INCORRECTO_INT, self::SIN_CONFIGURACION],
            ['null',                       null,     DatosSimples::CAMPO_INEXISTENTE,      self::SIN_CONFIGURACION],
            ['float_positivo',             1.2345,   DatosSimples::FORMATO_INCORRECTO_INT, self::SIN_CONFIGURACION],
            ['float_negativo',             -6.789,   DatosSimples::FORMATO_INCORRECTO_INT, self::SIN_CONFIGURACION],
            ['string_vacio',               '',       DatosSimples::FORMATO_INCORRECTO_INT, self::SIN_CONFIGURACION],
            ['string_vacio',               '',       DatosSimples::CAMPO_VACIO,            DatosSimples::INTERPRETAR_CADENAS],
            ['string_positivo',            '1.2345', DatosSimples::FORMATO_INCORRECTO_INT, DatosSimples::INTERPRETAR_CADENAS],
            ['string_negativo',            '-6.789', DatosSimples::FORMATO_INCORRECTO_INT, DatosSimples::INTERPRETAR_CADENAS],
            ['string_con_letras_antes',    'a1234',  DatosSimples::FORMATO_INCORRECTO_INT, DatosSimples::INTERPRETAR_CADENAS],
            ['string_con_letras_despues',  '1234a',  DatosSimples::FORMATO_INCORRECTO_INT, DatosSimples::INTERPRETAR_CADENAS],
            ['string_con_letras_en_medio', '12a34',  DatosSimples::FORMATO_INCORRECTO_INT, DatosSimples::INTERPRETAR_CADENAS]
        ];
    }

    /**
     * @dataProvider dataNumerosFlotantesValidos
     */
    public function testObtenerNumeroFlotante(string $clave, $valor, int $configuracion): void
    {
        $form = new DatosSimples([$clave => $valor], $configuracion);
        $resultado = $form->flotante($clave);
        $this->assertIsFloat($resultado);
        $this->assertEquals($valor, $resultado);
        $this->assertFalse($form->errores()->hay());
    }

    public function dataNumerosFlotantesValidos(): array
    {
        return [
            ['float_numero_positivo',  1.2345,    self::SIN_CONFIGURACION],
            ['float_numero_negativo',  -6.7890,   self::SIN_CONFIGURACION],
            ['string_numero_positivo', '1.2345',  DatosSimples::INTERPRETAR_CADENAS],
            ['string_numero_negativo', '-6.7890', DatosSimples::INTERPRETAR_CADENAS]
        ];
    }

    /**
     * @dataProvider dataNumerosFlotantesInvalidosConSusErrores
     */
    public function testErrorAlObtenerNumeroFlotante(string $clave, $valor, int $errorEsperado, int $configuracion): void
    {
        $form = new DatosSimples([$clave => $valor], $configuracion);
        $this->assertNull($form->flotante($clave));
        $this->assertSame($errorEsperado, $form->errores()->error());
    }

    public function dataNumerosFlotantesInvalidosConSusErrores(): array
    {
        return [
            ['objeto', new \stdClass(), DatosSimples::FORMATO_INCORRECTO_FLOAT,               self::SIN_CONFIGURACION],
            ['null',                      null,       DatosSimples::CAMPO_INEXISTENTE,        self::SIN_CONFIGURACION],
            ['bool_true',                 true,       DatosSimples::FORMATO_INCORRECTO_FLOAT, self::SIN_CONFIGURACION],
            ['bool_false',                false,      DatosSimples::FORMATO_INCORRECTO_FLOAT, self::SIN_CONFIGURACION],
            ['un_array',                  [],         DatosSimples::FORMATO_INCORRECTO_FLOAT, self::SIN_CONFIGURACION],
            ['int_positivo',              12345,      DatosSimples::FORMATO_INCORRECTO_FLOAT, self::SIN_CONFIGURACION],
            ['int_negativo',              -6789,      DatosSimples::FORMATO_INCORRECTO_FLOAT, self::SIN_CONFIGURACION],
            ['string_vacio',              '',         DatosSimples::FORMATO_INCORRECTO_FLOAT, self::SIN_CONFIGURACION],
            ['string_vacio_alt',          '',         DatosSimples::CAMPO_VACIO,              DatosSimples::INTERPRETAR_CADENAS],
            ['string_positivo',           '12345',    DatosSimples::FORMATO_INCORRECTO_FLOAT, DatosSimples::INTERPRETAR_CADENAS],
            ['string_negativo',           '-6789',    DatosSimples::FORMATO_INCORRECTO_FLOAT, DatosSimples::INTERPRETAR_CADENAS],
            ['string_con_letra_antes',    'a1.2345',  DatosSimples::FORMATO_INCORRECTO_FLOAT, DatosSimples::INTERPRETAR_CADENAS],
            ['string_con_letra_despues',  '1.2345a',  DatosSimples::FORMATO_INCORRECTO_FLOAT, DatosSimples::INTERPRETAR_CADENAS],
            ['string_con_letra_en_medio', '1.23a45',  DatosSimples::FORMATO_INCORRECTO_FLOAT, DatosSimples::INTERPRETAR_CADENAS],
            ['string_con_espacios',       ' 1.2345 ', DatosSimples::FORMATO_INCORRECTO_FLOAT, DatosSimples::INTERPRETAR_CADENAS],
            ['string_float_casi_valido',  '1.234567', DatosSimples::FORMATO_INCORRECTO_FLOAT, self::SIN_CONFIGURACION]
        ];
    }

    /**
     * @dataProvider dataEnterosInterpretadosComoFloats
     */
    public function testInterpretarEnterosComoFloats(string $clave, $valor, float $valorEsperado, int $configuracion): void
    {
        $form = new DatosSimples([$clave => $valor], $configuracion | DatosSimples::INTERPRETAR_ENTEROS_COMO_FLOAT);
        $resultado = $form->flotante($clave);
        $this->assertIsFloat($resultado);
        $this->assertSame($valorEsperado, $resultado);
    }

    public function dataEnterosInterpretadosComoFloats(): array
    {
        return [
            ['entero_positivo',        32,     32.0,   self::SIN_CONFIGURACION],
            ['entero_negativo',        -64,    -64.0,  self::SIN_CONFIGURACION],
            ['string_entero_positivo', '128',  128.0,  DatosSimples::INTERPRETAR_CADENAS],
            ['string_entero_negativo', '-256', -256.0, DatosSimples::INTERPRETAR_CADENAS],
        ];
    }

    /**
     * @dataProvider dataValoresBooleanosValidos
     */
    public function testObtenerValoresBooleanos(string $clave, $valor, bool $resultadoEsperado, int $configuracion): void
    {
        $form = new DatosSimples([$clave => $valor], $configuracion);
        $resultado = $form->booleano($clave);

        $this->assertNotNull($resultado);
        $this->assertSame($resultadoEsperado, $resultado);
        $this->assertFalse($form->errores()->hay());
    }

    public function dataValoresBooleanosValidos(): array
    {
        return [
            ['bool_true',          true,  true,  self::SIN_CONFIGURACION],
            ['bool_false',         false, false, self::SIN_CONFIGURACION],

            ['string_vacio_false', '',    false, DatosSimples::INTERPRETAR_CADENAS |
                                                 DatosSimples::INTERPRETAR_BOOLEANO_VACIO],

            ['string_numero_uno',  '1',   true,  DatosSimples::INTERPRETAR_CADENAS],
            ['string_on',          'on',  true,  DatosSimples::INTERPRETAR_CADENAS],
            ['string_oN',          'oN',  true,  DatosSimples::INTERPRETAR_CADENAS],
            ['string_On',          'On',  true,  DatosSimples::INTERPRETAR_CADENAS],
            ['string_ON',          'ON',  true,  DatosSimples::INTERPRETAR_CADENAS],

            ['string_numero_cero', '0',   false, DatosSimples::INTERPRETAR_CADENAS],
            ['string_off',         'off', false, DatosSimples::INTERPRETAR_CADENAS],
            ['string_ofF',         'ofF', false, DatosSimples::INTERPRETAR_CADENAS],
            ['string_oFf',         'oFf', false, DatosSimples::INTERPRETAR_CADENAS],
            ['string_oFF',         'oFF', false, DatosSimples::INTERPRETAR_CADENAS],
            ['string_Off',         'Off', false, DatosSimples::INTERPRETAR_CADENAS],
            ['string_OfF',         'OfF', false, DatosSimples::INTERPRETAR_CADENAS],
            ['string_OFf',         'OFf', false, DatosSimples::INTERPRETAR_CADENAS],
            ['string_OFF',         'OFF', false, DatosSimples::INTERPRETAR_CADENAS]
        ];
    }

    /**
     * @dataProvider dataValoresBooleanosInvalidosConSusErrores
     */
    public function testErrorAlObtenerUnValorBooleano(string $clave, $valor, int $errorEsperado, int $configuracion): void
    {
        $form = new DatosSimples([$clave => $valor], $configuracion);
        $this->assertNull($form->booleano($clave));
        $this->assertSame($errorEsperado, $form->errores()->error());
    }

    public function dataValoresBooleanosInvalidosConSusErrores(): array
    {
        return [
            ['objeto',   new \stdClass(),      DatosSimples::FORMATO_INCORRECTO_BOOL, self::SIN_CONFIGURACION],
            ['un_array',              [],      DatosSimples::FORMATO_INCORRECTO_BOOL, self::SIN_CONFIGURACION],
            ['string_cadena_vacia',   '',      DatosSimples::CAMPO_VACIO,             DatosSimples::INTERPRETAR_CADENAS],
            ['string_bool_true',      'true',  DatosSimples::FORMATO_INCORRECTO_BOOL, DatosSimples::INTERPRETAR_CADENAS],
            ['string_bool_false',     'false', DatosSimples::FORMATO_INCORRECTO_BOOL, DatosSimples::INTERPRETAR_CADENAS],
            ['string_on',             'on',    DatosSimples::FORMATO_INCORRECTO_BOOL, self::SIN_CONFIGURACION],
            ['string_off',            'off',   DatosSimples::FORMATO_INCORRECTO_BOOL, self::SIN_CONFIGURACION],
            ['valor_nulo',            null,    DatosSimples::CAMPO_INEXISTENTE,       self::SIN_CONFIGURACION],
            ['int_numero_positivo',   12345,   DatosSimples::FORMATO_INCORRECTO_BOOL, self::SIN_CONFIGURACION],
            ['int_numero_negativo',   -67890,  DatosSimples::FORMATO_INCORRECTO_BOOL, self::SIN_CONFIGURACION],
            ['float_numero_positivo', 1.2345,  DatosSimples::FORMATO_INCORRECTO_BOOL, self::SIN_CONFIGURACION],
            ['float_numero_negativo', -6.7890, DatosSimples::FORMATO_INCORRECTO_BOOL, self::SIN_CONFIGURACION]
        ];
    }

}
