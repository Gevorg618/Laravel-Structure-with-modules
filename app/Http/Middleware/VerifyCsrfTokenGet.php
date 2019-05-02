<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfTokenGet extends BaseVerifier
{
  /**
   * Determine if the HTTP request uses a ‘read’ verb.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return bool
   */
  protected function isReading($request)
  {
      return in_array($request->method(), ['HEAD', 'OPTIONS']);
  }
}
