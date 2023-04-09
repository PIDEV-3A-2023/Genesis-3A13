<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class BlobExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('blobToBase64', [$this, 'blobToBase64']),
        ];
    }

    public function blobToBase64($blob): string
    {
        return base64_encode(stream_get_contents($blob));
    }
}