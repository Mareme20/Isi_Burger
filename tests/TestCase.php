<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        // Stabilise les tests CI/Docker: pas de fichier sqlite requis.
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');

        // Les tests n'ont pas besoin des assets Vite.
        $this->withoutVite();
    }
}
