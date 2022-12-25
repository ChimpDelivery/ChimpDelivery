<?php

namespace Tests;

use App\Models\Workspace;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp() : void
    {
        parent::setUp();

        // default - ws required for app
        $this->defaultWs = Workspace::factory()->create(
            [ 'id' => 1 ]
        );
    }

}
