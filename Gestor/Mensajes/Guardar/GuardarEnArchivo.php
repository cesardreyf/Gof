<?php

namespace Gof\Gestor\Mensajes\Guardar;

use Gof\Datos\Bits\Mascara\MascaraDeBits;
use Gof\Interfaz\Archivos\Archivo;
use Gof\Interfaz\Mensajes\Guardable;

class GuardarEnArchivo implements Guardable
{
    const CONCATENAR = 1;

    const LIMPIAR_MENSAJE = 2;

    const RESETEAR_NBYTES_AL_GUARDAR = 4;

    const CONFIGURACION_POR_DEFECTO = self::CONCATENAR;

    /**
     *  @var Archivo Archivo donde se guardarán los mensajes
     */
    private $archivo;

    /**
     *  @var MascaraDeBits Configuracion interna
     */
    private $configuracion;

    /**
     *  @var int Número de bytes almacenados
     */
    private $bytes;

    /**
     *  Crea una instancia del gestor de guardado de mensajes
     *
     *  Gestor simple para almacenar mensajes de texto en archivo.
     *
     *  @param Archivo $archivo       Archivo existente donde se guardarán los mensajes
     *  @param int     $configuracion Máscara de bits con la configuración del gestor
     */
    public function __construct(Archivo $archivo, int $configuracion = self::CONFIGURACION_POR_DEFECTO)
    {
        $this->bytes = 0;
        $this->archivo = $archivo;
        $this->configuracion = new MascaraDeBits($configuracion);
    }

    /**
     *  Guarda el mensaje en el archivo existente
     *
     *  @param string $mensaje Mensaje de texto a ser guardado
     *
     *  @return bool Devuelve TRUE en caso de éxito o FALSE de lo contrario
     */
    public function guardar(string $mensaje): bool
    {
        $configuracion = 0;
        if( empty($mensaje) ) {
            return false;
        }

        if( $this->configuracion->activados(self::CONCATENAR) ) {
            $configuracion = FILE_APPEND;
        }

        if( $this->configuracion->activados(self::LIMPIAR_MENSAJE) ) {
            $mensaje = trim($mensaje);
        }

        if( $this->configuracion->activados(self::RESETEAR_NBYTES_AL_GUARDAR) ) {
            $this->bytes = 0;
        }

        // Aunque file_put_contents no necesita que el archivo exista como tal el gestor lo
        // hace implícito al requerir un dato de tipo Archivo, ya que este sí lo requiere.
        $resultado = file_put_contents($this->archivo->ruta(), $mensaje, $configuracion);

        if( $resultado === false ) {
            return false;
        }

        // Número de bytes guardados
        $this->bytes += $resultado;
        return true;
    }

    /**
     *  Obtiene el número de bytes almacenados en el archivo
     *
     *  La cantidad de bytes es acumulativo por defecto. Esto quiere decir que con cada nuevo
     *  mensaje el número de bytes se sumará al anterior.
     *
     *  Si la flag RESETEAR_NBYTES_AL_GUARDAR está activo, cada vez que se guarde un mensaje
     *  el número de bytes se establecerá a cero antes del guardado por lo que el resultado
     *  siempre será el tamaño en bytes del último mensaje guardado.
     *
     *  @return int Devuelve el número de bytes guardados
     */
    public function bytes(): int
    {
        return $this->bytes;
    }

    /**
     *  Obtiene la configuración interna
     *
     *  @return MascaraDeBits Devuelve la configuración interna del gestor
     */
    public function configuracion(): MascaraDeBits
    {
        return $this->configuracion;
    }

}
