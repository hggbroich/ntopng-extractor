<?php

namespace App\Controller\Flow;

use App\Repository\FlowRepositoryInterface;
use App\Repository\PaginationQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class IndexAction extends AbstractController {

    #[Route('/flow', name: 'flows')]
    public function __invoke(
        FlowRepositoryInterface $flowRepository,
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter(filter: FILTER_DEFAULT, flags: FILTER_FLAG_EMPTY_STRING_NULL | FILTER_NULL_ON_FAILURE)] string|null $ip = null,
        #[MapQueryParameter(filter: FILTER_DEFAULT, flags: FILTER_FLAG_EMPTY_STRING_NULL | FILTER_NULL_ON_FAILURE)] string|null $l4proto = null,
        #[MapQueryParameter(filter: FILTER_DEFAULT, flags: FILTER_FLAG_EMPTY_STRING_NULL | FILTER_NULL_ON_FAILURE)] string|null $l7proto = null,
        #[MapQueryParameter(filter: FILTER_DEFAULT, flags: FILTER_FLAG_EMPTY_STRING_NULL | FILTER_NULL_ON_FAILURE)] string|null $hostname = null,
    ): Response {
        $flows = $flowRepository->find(new PaginationQuery(page: $page), $ip, $l4proto, $l7proto, $hostname);

        $l4protos = $flowRepository->findL4Proto();
        $l7protos = $flowRepository->findL7Proto();

        return $this->render('flows/index.html.twig', [
            'flows' => $flows,
            'l4proto' => $l4proto,
            'l7proto' => $l7proto,
            'l4protos' => $l4protos,
            'l7protos' => $l7protos,
            'ip' => $ip,
            'hostname' => $hostname
        ]);
    }
}
