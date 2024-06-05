<?php

use App\Models\Tests\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testThatWeCanGetFirstName()
    {
        $user = new User;

        $user->setFirstName('Billy');

        $this->assertEquals('Billy', $user->getFirstName());
    }

    public function testThatWeCanGetLastName()
    {
        $user = new User;

        $user->setLastName('Golding');

        $this->assertEquals('Golding', $user->getLastName());
    }

    public function testThatWeCanGetFullName()
    {
        $user = new User;
        $user->setFirstName('Billy');
        $user->setLastName('Golding');

        $this->assertEquals('Billy Golding', $user->getFullName());
    }
}