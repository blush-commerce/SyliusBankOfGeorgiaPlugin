<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Controller;

use Symfony\Component\HttpFoundation\Response;

final class PaymentStatusChangeCallbackController
{
    public function __construct()
    {
    }

    public function __invoke(): Response
    {
        return new Response(null, 200);
    }
}
