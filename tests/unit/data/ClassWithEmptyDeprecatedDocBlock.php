<?php /** @noinspection PhpIllegalPsrClassPathInspection */

declare(strict_types=1);

/** @deprecated */
class ClassWithEmptyDeprecatedDocBlock
{
    /** @deprecated This method is deprecated, because should be removed asap */
    public function someDeprecatedMethodWithReason(): void
    {
    }

    /** @deprecated  */
    public function someDeprecatedMethod(): void
    {
    }
}
