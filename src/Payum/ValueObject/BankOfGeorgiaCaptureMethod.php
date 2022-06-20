<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Payum\ValueObject;

final class BankOfGeorgiaCaptureMethod
{
    public function __construct(
        private string $captureMethod
    )
    {
    }

    public function getCaptureMethod(): string
    {
        return $this->captureMethod;
    }
}
