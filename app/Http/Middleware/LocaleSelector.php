<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Http\Request;

class LocaleSelector
{
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * @var \Illuminate\Contracts\Translation\Translator
     */
    protected $translator;

    /**
     * SetLocaleFromHeader constructor.
     *
     * @param  \Illuminate\Contracts\Config\Repository      $config
     * @param  \Illuminate\Contracts\Translation\Translator $translator
     */
    public function __construct(Repository $config, Translator $translator)
    {
        $this->config = $config;
        $this->translator = $translator;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
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
