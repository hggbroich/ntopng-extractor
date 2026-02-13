<?php

declare(strict_types=1);

namespace App\Twig;

use ramazancetinkaya\ByteFormatter;
use Twig\Attribute\AsTwigFilter;

class ByteFormatterExtension {

    public function __construct(
        private readonly ByteFormatter $byteFormatter
    ) {

    }

    #[AsTwigFilter('bytes')]
    public function formatBytes(int $bytes): string {
        return $this->byteFormatter->convert($bytes, toBinary: false);
    }
}
