<?php

namespace App\Controller\Domain;

use App\Repository\FlowRepositoryInterface;
use App\Repository\PaginationQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class DomainsAction extends AbstractController {
    #[Route('/domain', name: 'domains')]
    public function __invoke(
        FlowRepositoryInterface $flowRepository,
        #[MapQueryParameter] int $page = 1,
    ): Response {
        $info = $flowRepository->findInfo(new PaginationQuery(page: $page, limit: 25));

        return $this->render('domains/index.html.twig', [
            'info' => $info
        ]);
    }
}
