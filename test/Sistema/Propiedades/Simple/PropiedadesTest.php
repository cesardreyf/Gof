<?php

declare(strict_types=1);

namespace Test\Sistema\Propiedades\Simple;

use Gof\Sistema\Propiedades\Simple\Propiedades;
use PHPUnit\Framework\TestCase;

class PropiedadesTest extends TestCase
{

    public function testAlgo(): void
    {
        $propiedades = new Propiedades();
        $this->assertTrue(true);
    }

}
