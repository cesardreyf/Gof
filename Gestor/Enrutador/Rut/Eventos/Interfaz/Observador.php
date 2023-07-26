<?php

namespace Gof\Gestor\Enrutador\Rut\Eventos\Interfaz;

interface Observador
{
    public function alAgregar(IRuta $ruta);
    public function alProcesar(IRuta $ruta);
}
