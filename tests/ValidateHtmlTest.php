<?php

namespace App\Tests;

use HtmlValidator\Response;
use HtmlValidator\Validator;

class ValidateHtmlTest extends PingTest
{
    protected static $pages = [
        '/',
        '/login',
    ];

    /** @var Validator */
    private $validator;

    public function __construct()
    {
        parent::__construct();
        $this->validator = new Validator();
        $this->validator->setParser(Validator::PARSER_HTML5);
    }

    public function testValidate(): void
    {
        foreach (self::$pages as $page) {
            $response = $this->visit($page);

            /** @var Response $result */
            $result = $this->validator->validateDocument($response->getContent());

            if (!$result->hasErrors() && !$result->hasWarnings()) {
                print \sprintf("\nValidating page '%s': ok\n", $page);
                continue;
            }

            print \sprintf("\nError found on page '%s': \n", $page);
            print $result . "\n";
        }
    }
}
