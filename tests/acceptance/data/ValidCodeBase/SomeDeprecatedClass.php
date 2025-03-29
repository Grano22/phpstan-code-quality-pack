<?php

declare(strict_types=1);

namespace data\ValidCodeBase;

use Deprecated;

/**
 * @deprecated
 */
final class SomeDeprecatedClass
{
    #[Deprecated]
    public function deprecatedMethod(): void
    {
        trigger_deprecation('grano22/code_quality_pack', '0.0.1', 'Do not use it!');
    }
}
