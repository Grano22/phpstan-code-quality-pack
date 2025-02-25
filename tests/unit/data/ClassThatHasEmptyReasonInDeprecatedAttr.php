<?php /** @noinspection PhpIllegalPsrClassPathInspection */

declare(strict_types=1);

class ClassThatHasEmptyReasonInDeprecatedAttr
{
    #[Deprecated]
    public function someDeprecatedMethod(): void
    {
    }

    #[Deprecated('')]
    public function someDeprecatedMethodWithEmptyReason(): void
    {
    }

    #[Deprecated(message: '')]
    public function someDeprecatedMethodWithEmptyReasonAsNamedArgument(): void
    {
    }

    #[Deprecated(since: '2025-02-25 11:12:11')]
    public function someDeprecatedMethodWithFilledOtherArgument(): void
    {
    }

    #[Deprecated(message: 'Method was not implemented till idea')]
    public function someCorrectlyDeprecatedMethod(): void
    {
    }
}
