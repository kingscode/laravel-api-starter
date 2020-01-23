<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Http\Request;

final class LocaleSelector
{
    private Repository $config;

    private Translator $translator;

    public function __construct(Repository $config, Translator $translator)
    {
        $this->config = $config;
        $this->translator = $translator;
    }

    public function handle(Request $request, Closure $next)
    {
        if ($request->headers->has('Accept-Language')) {
            $locale = $request->headers->get('Accept-Language');

            $this->config->set('app.locale', $locale);

            $this->translator->setLocale($locale);
        }

        return $next($request);
    }
}
