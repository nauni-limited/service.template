<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Nauni\Bundle\NauniTestSuiteBundle\Attribute\Suite;

#[Suite('another')]
class AnotherController
{
    #[Route('/another/number', name: 'another_number', methods: ['GET', 'HEAD'])]

    public function number(): JsonResponse
    {
        $number = random_int(0, 100);

        return new JsonResponse(
            ['number' => $number]
        );
    }
}
