<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class HelloController extends AbstractController
{
    public function sayHello(): Response
    {
        return new Response("Hello There!");
    }
}
