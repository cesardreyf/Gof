<?php

namespace Gof\Gestor\Autoload;

use Gof\Datos\Bits\Mascara\MascaraDeBits;
use Gof\Gestor\Autoload\Excepcion\CadenaInvalidaParaCargar;
use Gof\Gestor\Autoload\Excepcion\EspacioDeNombreInexistente;
use Gof\Gestor\Autoload\Excepcion\EspacioDeNombreInvalido;
use Gof\Gestor\Autoload\Excepcion\ObjetoExistente;
use Gof\Gestor\Autoload\Excepcion\ObjetoInexistente;
use Gof\Gestor\Autoload\Interfaz\Cargador;
use Gof\Gestor\Autoload\Interfaz\Filtro;
use Gof\Interfaz\Archivos\Carpeta;
use Gof\Interfaz\Bits\Mascara;

class Autoload
{
    /**
     *  @var int Flag reservado
     */
    const AUTOLOAD_REGISTRADO = 1;

    /**
     *  @var int Flag para cargar el archivo al llamar a la función *instanciar*
     */
    const CARGAR_AL_INSTANCIAR = 8;

    /**
     *  @var int Flag para desregistrar la función autoload al destruirse la instancia del gestor
     */
    const DESREGISTRAR_AUTOLOAD_AL_DESTRUIRSE = 16;

    /**
     *  @var int Flag para lanzar excepciones en casos de errores, en lugar de otros métodos más silenciosos
     */
    const LANZAR_EXCEPCIONES = 32;

    /**
     *  @var int Flag para ser estricto con las validaciones. Si alguna falla se lanzan excepciones en vez de devolver un valor booleano
     */
    const MODO_ESTRICTO = 64;

    /**
     *  @var int Flag para indicar que los espacios de nombres se pueden reemplazar al reservarse con el mismo nombre
     */
    const REEMPLAZAR_ESPACIOS_DE_NOMBRE = 128;

    /**
     *  @var int Máscara de bits con la configuración por defecto
     */
    const CONFIGURACION_POR_DEFECTO = self::LANZAR_EXCEPCIONES
                                    | self::REEMPLAZAR_ESPACIOS_DE_NOMBRE
                                    | self::DESREGISTRAR_AUTOLOAD_AL_DESTRUIRSE;

    /**
     *  @var int Indica que hubo un error del filtro
     */
    const ERROR_EN_EL_FILTRO = 100;

    /**
     *  @var int Indica que hubo un error del cargador
     */
    const ERROR_EN_EL_CARGADOR = 101;

    /**
     *  @var int Indica que ya existe una clase, interfaz o trait con el mismo nombre
     */
    const ERROR_OBJETO_EXISTENTE = 200;

    /**
     *  @var int Indica que no existe ninguna clase, interfaz o trait
     */
    const ERROR_OBJETO_INEXISTENTE = 201;

    /**
     *  @var int Indica que no existe ningún espacio de nombre correspondiente
     */
    const ERROR_NAMESPACE_INEXISTENTE = 300;

    /**
     *  @var int Indica que el nombre de espacio ya está reservado
     */
    const ERROR_NAMESPACE_RESERVADO = 310;

    /**
     *  @var int Indica que la función autoload ya está registrado
     */
    const ERROR_AUTOLOAD_REGISTRADO = 500;

    /**
     *  @var int Indica que aún no se registró ninguna función para el autoload
     */
    const ERROR_AUTOLOAD_NO_REGISTRADO = 501;

    /**
     *  @var int Almacena el último error ocurrido en el gestor
     */
    private $error;

    /**
     *  @var int Máscara de bit con la configuración del gestor
     */
    private $configuracion;

    /**
     *  @var Filtro Filtro que se aplicarán para las cadenas de Espacios de Nombres y Clases
     */
    private $filtro;

    /**
     *  @var Cargador Subgestor encargado de cargar los archivos
     */
    private $cargador;

    /**
     *  @var array Lista de espacios de nombres reservados
     */
    private $espaciosDeNombres;

    /**
     *  Crea una instancia del gestor de autoload
     *
     *  @param int $configuracion Máscara de bit con la configuración
     */
    public function __construct(Cargador $cargador, Filtro $filtro, int $configuracion = self::CONFIGURACION_POR_DEFECTO)
    {
        $this->error = 0;
        $this->filtro($filtro);
        $this->cargador($cargador);
        $this->espaciosDeNombres = array();
        $this->configuracion = new MascaraDeBits($configuracion);
    }

    /**
     *  Destructor
     */
    public function __destruct()
    {
        if( $this->configuracion->activados(self::DESREGISTRAR_AUTOLOAD_AL_DESTRUIRSE) ) {
            $this->desregistrar();
        }
    }

    /**
     *  Carga un archivo según el nombre de la clase, interfaz o trait correspondiente
     *
     *  Aplica un filtro al nombre y si pasa esta y otras validaciones carga el archivo dejando disponible
     *  la clase, interfaz o trait para su posterior uso.
     *
     *  **Según la configuración**:
     *
     *  Si está activo LANZAR_EXCEPCIONES y cargó correctamente el archivo pero no se encuentra ninguna clase,
     *  interfaz o trait dentro que corresponda con el nombre será lanzada una excepción, de lo contrario la función
     *  devolverá un valor de tipo bool: **false** y se almacenará el identificador del error.
     *  El último error ocurrido puede ser visto mediante el método **error()**.
     *
     *  Si está activo el MODO_ESTRICTO y al aplicar el filtro para las clases al nombre pasado como argumento
     *  devuelve falso, en lugar de retornar **false** lanzará una excepción. Lo mismo hará si se intenta
     *  cargar un objeto (clase, interface, trait) que ya existe o si no existe ningún espacio de nombre que
     *  corresponda con el objeto en cuestión.
     *
     *  El MODO_ESTRICTO solo servirá si está activo LANZAR_EXCEPCIONES, caso contrario no será tenido en cuenta.
     *
     *  @param string $nombre Nombre de la clase, interfaz o trait a ser cargado
     *
     *  @throws CadenaInvalidaParaCargar   si el filtro devuelve false y está activo el MODO_ESTRICTO
     *  @throws EspacioDeNombreInexistente si el nombre no corresponde a ningún espacio de nombre reservado y está activo el MODO_ESTRICTO
     *  @throws ObjetoExistente            si ya está cargado una clase, interfaz o trait con el mismo nombre y está activo MODO_ESTRICTO
     *  @throws ObjetoInexistente          si no existe ninguna clase, interfaz o trait dentro del archivo correspondiente y está activo LANZAR_EXCEPCIONES
     *
     *  @see Autoload::error() Para ver el error ocurrido en caso de que falle la carga
     *
     *  @return bool Devuelve **true** en caso de éxito o **false** de lo contrario
     */
    public function cargar(string $nombre): bool
    {
        if( $this->filtro->clase($nombre) === false ) {
            if( $this->configuracion->activados(self::MODO_ESTRICTO, self::LANZAR_EXCEPCIONES) ) {
                throw new CadenaInvalidaParaCargar($nombre);
            }

            $this->agregarError(self::ERROR_EN_EL_FILTRO);
            return false;
        }

        if( $this->objetoExiste($nombre) ) {
            if( $this->configuracion->activados(self::MODO_ESTRICTO, self::LANZAR_EXCEPCIONES) ) {
                throw new ObjetoExistente($nombre);
            }

            $this->agregarError(self::ERROR_OBJETO_EXISTENTE);
            return false;
        }

        $nombreDelArchivo = '';
        $espacioDeNombreDeLaClase = '';
        $this->obtenerEspacioDeNombreYNombreDelArchivo($nombre, $espacioDeNombreDeLaClase, $nombreDelArchivo);

        if( isset($this->espaciosDeNombres[$espacioDeNombreDeLaClase]) === false ) {
            if( $this->configuracion->activados(self::MODO_ESTRICTO, self::LANZAR_EXCEPCIONES) ) {
                throw new EspacioDeNombreInexistente($espacioDeNombreDeLaClase, $nombre);
            }

            $this->agregarError(self::ERROR_NAMESPACE_INEXISTENTE);
            return false;
        }

        $carpeta = $this->espaciosDeNombres[$espacioDeNombreDeLaClase]->ruta();
        $rutaDelArchivo = $carpeta . $nombreDelArchivo;

        if( $this->cargador->cargar($rutaDelArchivo) === false ) {
            $this->agregarError(self::ERROR_EN_EL_CARGADOR);
            return false;
        }

        if( $this->objetoExiste($nombre) === false ) {
            if( $this->configuracion->activados(self::LANZAR_EXCEPCIONES) ) {
                throw new ObjetoInexistente($rutaDelArchivo, $nombre);
            }

            $this->agregarError(self::ERROR_OBJETO_INEXISTENTE);
            return false;
        }

        return true;
    }

    /**
     *  Crea una instancia de una clase según una cadena de caracteres
     *
     *  @param string $nombreDeLaClase Nombre completo de la clase
     *  @param mixed  ...$argumentos   Argumentos que se le pasarán a la clase por el constructor
     *
     *  @return object|null Devuelve una instancia de la clase si existe o **NULL** de lo contrario
     */
    public function instanciar(string $nombreDeLaClase, ...$argumentos): ?object
    {
        if( class_exists($nombreDeLaClase, false) === false ) {
            if( $this->configuracion->desactivados(self::CARGAR_AL_INSTANCIAR) ) {
                return null;
            }

            $this->cargar($nombreDeLaClase);
        }

        return new $nombreDeLaClase(...$argumentos);
    }

    /**
     *  Registra la función autoload del gestor
     *
     *  @return bool Devuelve **true** en caso de éxito o **false** de lo contrario
     */
    public function registrar(): bool
    {
        if( $this->configuracion->activados(self::AUTOLOAD_REGISTRADO) ) {
            $this->agregarError(self::ERROR_AUTOLOAD_REGISTRADO);
            return false;
        }

        if( spl_autoload_register([$this, 'cargar']) === true ) {
            $this->configuracion->activar(self::AUTOLOAD_REGISTRADO);
            return true;
        }

        $this->agregarError(self::ERROR_AUTOLOAD_NO_REGISTRADO);
        return false;
    }

    /**
     *  Desregistra la función autoload del gestor
     *
     *  @return bool Devuelve **true** en caso de éxito o **false** de lo contrario
     */
    public function desregistrar(): bool
    {
        if( $this->configuracion->activados(self::AUTOLOAD_REGISTRADO) ) {
            if( spl_autoload_unregister([$this, 'cargar']) === true ) {
                $this->configuracion->desactivar(self::AUTOLOAD_REGISTRADO);
                return true;
            }
        }

        $this->agregarError(self::ERROR_AUTOLOAD_NO_REGISTRADO);
        return false;
    }

    /**
     *  Reserva un espacio de nombre
     *
     *  Agrega a la lista interna de espacios de nombres reservados el namespace y una carpeta
     *  donde se buscarán los archivos correspondientes.
     *
     *  @param string  $espacioDeNombre Namespace a reservar
     *  @param Carpeta $carpeta         Carpeta donde se buscarán los archivos
     *
     *  @throws EspacioDeNombreInvalido si está activo MODO_ESTRICTO y no pasa el filtro
     *
     *  @return bool Devuelve **true** si el espacio de nombre se reservó correctamente o **false** de lo contrario
     */
    public function reservar(string $espacioDeNombre, Carpeta $carpeta): bool
    {
        if( $this->filtro->espacioDeNombre($espacioDeNombre) === false ) {
            if( $this->configuracion->activados(self::MODO_ESTRICTO, self::LANZAR_EXCEPCIONES) ) {
                throw new EspacioDeNombreInvalido($espacioDeNombre);
            }

            $this->agregarError(self::ERROR_EN_EL_FILTRO);
            return false;
        }

        if( isset($this->espaciosDeNombres[$espacioDeNombre]) ) {
            if( $this->configuracion->desactivados(self::REEMPLAZAR_ESPACIOS_DE_NOMBRE) ) {
                $this->agregarError(self::ERROR_NAMESPACE_RESERVADO);
                return false;
            }
        }

        $this->espaciosDeNombres[$espacioDeNombre] = $carpeta;
        return true;
    }

    /**
     *  Obtiene un array con los espacios de nombres como claves y las carpetas como valores
     *
     *  @return array Devuelve un array con los espacios de nombres reservados
     */
    public function espaciosDeNombres(): array
    {
        return $this->espaciosDeNombres;
    }

    /**
     *  Obtiene el gestor de configuracion interna
     *
     *  @return Configuracion Devuelve el gestor de configuración
     */
    public function configuracion(): Mascara
    {
        return $this->configuracion;
    }

    /**
     *  Obtiene y/o define el filtro a ser utilizado por el gestor
     *
     *  @param Filtro|null $filtro Instancia del filtro o **NULL** para obtener el actual
     *
     *  @return Filtro Devuelve el filtro actual
     */
    public function filtro(?Filtro $filtro = null): Filtro
    {
        return $filtro === null ? $this->filtro : $this->filtro = $filtro;
    }

    /**
     *  Obtiene y/o define el subgestor de cargas de los archivos
     *
     *  @param Cargador|null $cargador Instancia del subgestor de carga de archivos o **NULL** para obtener el actual
     *
     *  @return Cargador Devuelve el subgestor de cargas de archivos actual
     */
    public function cargador(?Cargador $cargador = null): Cargador
    {
        return $cargador === null ? $this->cargador : $this->cargador = $cargador;
    }

    /**
     *  Devuelve el último error ocurrido
     *
     *  @return int Devuelve el último error
     */
    public function error(): int
    {
        return $this->error;
    }

    /**
     *  Limpia los errores
     */
    public function limpiarErrores()
    {
        $this->error = 0;
    }

    /**
     *  Valida si el objeto existe o no
     *
     *  Según el nombre del objeto devuelve **true** si existe una clase, interfaz o trait con el mismo nombre.
     *  Caso contrario devuelve **false**.
     *
     *  @param string $objeto Nombre de la clase, interfaz o trait
     *
     *  @return bool Devuelve **true** si existe la clase, interfaz o trait
     */
    private function objetoExiste(string $objeto): bool
    {
        return class_exists($objeto, false) || interface_exists($objeto, false) || trait_exists($objeto, false);
    }

    private function obtenerEspacioDeNombreYNombreDelArchivo(string $nombre, string& $espacioDeNombreDeLaClase, string& $nombreDelArchivo): void
    {
        $pos = strpos($nombre, '\\');
        $espacioDeNombreDeLaClase = substr($nombre, 0, $pos);
        $nombreDelArchivo = trim(str_replace('\\', DIRECTORY_SEPARATOR, substr($nombre, $pos)), DIRECTORY_SEPARATOR);
    }

    private function agregarError(int $error)
    {
        $this->error = $error;
    }

}
