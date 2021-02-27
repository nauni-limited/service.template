<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use WeDoCode\Bundle\WeDoCodeTestSuiteBundle\Attribute\Suite;

#[Suite(['controller', 'unlucky'])]
class UnLuckyController
{
    #[Route('/unlucky/number', name: 'unluck_number', methods: ['GET', 'HEAD'])]

    public function number(): JsonResponse
    {
        $number = random_int(0, 100);

        return new JsonResponse(
            ['number' => $number]
        );
    }
}
