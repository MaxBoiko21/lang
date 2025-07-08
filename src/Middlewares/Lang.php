<?php

namespace SmartCms\Lang\Middlewares;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Context;

/**
 * Class Lang
 *
 * Prepends the language to the request.
 */
class Lang
{
    /**
     * Handles the request.
     *
     * @param  Request  $request  The request to handle.
     */
    public function handle(Request $request, Closure $next): Response | JsonResponse
    {
        $source = $request->path();
        if ($request->path() == 'livewire/update') {
            $referer = $request->header('referer');
            if ($referer) {
                $source = parse_url($referer)['path'] ?? url('/');
            }
        }
        $segments = explode('/', trim($source, '/'));
        if (count($segments) <= 0) {
            return $next($request);
        }
        $potentialLang = $segments[0];
        if (strlen($potentialLang) === 2) {
            $lang = app('lang')->getBySlug($potentialLang);
            if ($lang) {
                if (! app('lang')->isFrontendAvailable($lang->slug)) {
                    abort(404);
                }
                app()->setLocale($lang->locale);
                Context::add('current_lang', $lang->slug);
                app('lang')->setCurrent($lang->slug);
            }
        }
        return $next($request);
    }
}
