<?php

namespace Gof\Gestor\Enrutador\Simple;

use Gof\Interfaz\Enrutador\Enrutador as IEnrutador;
use Gof\Interfaz\Lista\Textos as Lista;

/**
 * Clase encargada de generar un nombre de clase en base a una petición
 *
 * En base a una lista de objetivos y otra lista de controladores disponibles
 * genera un nombre de una clase válido para ser instanciado.
 *
 * @package Gof\Gestor\Enrutador\Simple
 */
class Enrutador implements IEnrutador
{
    /**
     * @var string Nombre completo de la clase
     */
    private $nombreClase = '';

    /**
     * Constructor
     *
     * En base a una lista de objetivos busca en una determinada carpeta de forma
     * secuencial los archivos o carpetas correspondientes. Una vez encontrado el
     * fichero en cuestión se almacena la ruta completa en el formato de una clase
     * junto a su namespace.
     *
     * En caso de no encontrarse el objetivo se almacenará como nombre de clase el
     * indicado por el parámetro $inexistente, si este existe; de no hacerlo
     * se lanza una excepción.
     *
     * Si en la lista de elementos $disponibles existe un array este se considera
     * una carpeta. Si hay coincidencia y existe un elemento más en $objetivos la
     * la próxima búsqueda se hará dentro de dicha carpeta. Si no existen más
     * elementos en $objetivos y la última coincidencia es una carpeta se concidera
     * como clase el valor indicado por el parámetro $principal.
     * Por lo que se asume que cada carpeta contiene un archivo llamado tal y como
     * se establece en $principal.
     *
     * Si la lista de $objetivos está vacía el nombre de la clase será el indicado
     * por el parámetro $principal, sin ningún namespace.
     *
     * @param Lista $objetivos Lista con elementos de tipo texto con los objetivos
     * @param string[]|array<string, array> $disponibles Array con los elementos disponibles y accesibles
     * @param string $carpeta Carpeta donde se buscarán los archivos/clase
     * @param string $principal Nombre de los archivos considerados principales
     * @param string $inexistente Nombre del archivo utilizado en caso de inexistencia
     *
     * @throws Exception Si no existe ningún archivo que coincida con $principal o $inexistente
     */
    public function __construct(Lista $objetivos, array $disponibles, string $principal, string $inexistente)
    {
        $espacioDeNombre = '';
        $nombreDeLaClase = ucfirst($principal);

        foreach( $objetivos->lista() as $objetivo ) {
            if( in_array($objetivo, $disponibles) ) {
                $nombreDeLaClase = ucfirst($objetivo);
                break;
            }

            if( isset($disponibles[$objetivo]) && is_array($disponibles[$objetivo]) ) {
                $espacioDeNombre .= ucfirst($objetivo) . '\\';
                $nombreDeLaClase = ucfirst($principal);
                $disponibles = $disponibles[$objetivo];
                continue;
            }

            $nombreDeLaClase = ucfirst($inexistente);
            $espacioDeNombre = '';
            break;
        }

        $this->nombreClase = $espacioDeNombre . $nombreDeLaClase;
    }

    /**
     * Nombre de la clase
     *
     * @return string Retorna el nombre de la clase junto a su namespace correspondiente
     */
    public function nombreClase(): string
    {
        return $this->nombreClase;
    }

}
