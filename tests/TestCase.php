<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{

    public function postRequest(string $url, string $method, array $data, array $overrides = [], int $id = 0): TestResponse 
    {
        if (!empty($overrides)) {
            $data = array_merge($data, $overrides);
        }

        if (in_array(strtoupper($method), ['PUT', 'PATCH']) && !$id) {
            throw new InvalidArgumentException("An ID must be provided for {$method} requests.");
        }
        return match (strtoupper($method)) {
            'POST'   => $this->postJson(route($url), $data),
            'PUT'    => $this->putJson(route($url, $id), $data),
            'PATCH'  => $this->patchJson(route($url, $id), $data),
            'DELETE' => $this->deleteJson(route($url, $id), $data),
            default  => throw new InvalidArgumentException("Unsupported HTTP method: {$method}"),
        };
    }
}
