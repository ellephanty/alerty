<?php

require_once __DIR__ . '/../vendor/autoload.php';

use function Ellephanty\Alerty\exception_email;

$dotenv = new Dotenv\Dotenv(__DIR__ . '/..');
$dotenv->load();

class EmailExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testEmail()
    {
        exception_email(new Exception('test'), 'Error test', ['myAttribute' => 'value']);
    }
}
