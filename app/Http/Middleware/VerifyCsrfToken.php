<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'api/v1.0/company/*',
        'api/v1.0/client*',
        'api/v1.0/vehicle*',
        'api/v1.0/parking-place/*',
        'api/v1.0/payment/*',
    ];
}
