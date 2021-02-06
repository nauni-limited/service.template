<?php

namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use WeDoCode\Bundle\WeDoCodeTestSuiteBundle\Attribute\Suite;

#[Suite('lucky')]
class LuckyController
{
    #[Route('/lucky/number', name: 'luck_number', methods: ['GET', 'HEAD'])]
    public function number(): JsonResponse
    {
        $number = random_int(0, 100);

        return new JsonResponse(
            ['number' => $number]
        );
    }
}