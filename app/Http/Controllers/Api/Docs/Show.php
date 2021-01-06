<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Docs;

use App\Contracts\Http\Responses\ResponseFactory;
use Illuminate\Http\Response;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use function file_exists;
use function file_get_contents;

final class Show
{
    private ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(string $doc = 'readme'): Response
    {
        $path = __DIR__ . "/../../../../../.docs/api/{$doc}.md";

        if (! file_exists($path)) {
            return $this->responseFactory->noContent(Response::HTTP_NOT_FOUND);
        }

        $content = file_get_contents($path);

        return $this->responseFactory->make(
            $this->buildConverter()->convertToHtml($content)
        );
    }

    private function buildConverter(): CommonMarkConverter
    {
        $environment = Environment::createCommonMarkEnvironment();

        $environment->addExtension(new GithubFlavoredMarkdownExtension());

        return new CommonMarkConverter([], $environment);
    }
}
