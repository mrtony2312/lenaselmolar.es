<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Permanent (301) redirects for URLs that changed during the ES-only migration:
 * PT product/category slugs, and the /product//category URL bases.
 * Map lives in config/redirects.php  (old path => new path, no query string).
 */
class LegacyRedirects
{
    public function handle(Request $request, Closure $next): Response
    {
        $path = '/'.ltrim($request->getPathInfo(), '/');
        $path = $path !== '/' ? rtrim($path, '/') : $path;

        $map = config('redirects', []);

        if (isset($map[$path]) && $map[$path] !== $path) {
            $target = $map[$path];
            $qs = $request->getQueryString();

            return redirect($qs ? $target.'?'.$qs : $target, 301);
        }

        return $next($request);
    }
}
