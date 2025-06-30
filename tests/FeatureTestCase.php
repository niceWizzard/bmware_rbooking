<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class FeatureTestCase extends BaseTestCase
{
    protected function setUp(): void {
        parent::setUp();
        $this->artisan('migrate',
            [
                '--path' => 'database/migrations/test',
                '--force' => true,
            ]
        );
    }

}
