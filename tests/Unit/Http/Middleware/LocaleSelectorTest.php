<?php

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\LocaleSelector;
use Illuminate\Http\Request;
use Tests\TestCase;

class LocaleSelectorTest extends TestCase
{
    public function test()
    {
        /** @var LocaleSelector $localeSelector */
        $localeSelector = $this->app->make(LocaleSelector::class);

        $request = Request::capture();

        $request->headers->set('Accept-Language', 'nl');

        $this->assertSame('en', $this->app->getLocale());

        $response = $localeSelector->handle($request, function () {
            return $this->app->getLocale();
        });

        $this->assertSame('nl', $response);
    }
}
