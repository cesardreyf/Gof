<?php

declare(strict_types=1);

namespace Test\Gestor\Session;

use Gof\Contrato\Session\Session as ISession;
use Gof\Gestor\Session\Session;
use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase
{

    public function setUp(): void
    {
        $this->session = new Session();
    }

    public function testAlgo(): void
    {
        $this->assertInstanceOf(ISession::class, $this->session);
    }

}
