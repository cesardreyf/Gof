<?php

declare(strict_types=1);

namespace Test\Gestor\Cookies;

use Gof\Contrato\Cookies\Cookies as ICookies;
use Gof\Gestor\Cookies\Cookies;
use PHPUnit\Framework\TestCase;

class CookiesTest extends TestCase
{

    public function testImplementElContratoCookie(): void
    {
        $cookies = new Cookies();
        $this->assertInstanceOf(ICookies::class, $cookies);
    }

    // TAREA

}
