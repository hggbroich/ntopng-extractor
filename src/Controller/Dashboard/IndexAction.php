<?php

namespace App\Controller\Dashboard;

use App\Repository\FlowRepositoryInterface;
use App\Repository\PaginationQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class IndexAction extends AbstractController {
    #[Route('/', name: 'dashboard')]
    public function __invoke(
        FlowRepositoryInterface $flowRepository,
    ): Response {
        $flowCount = $flowRepository->countFlows();
        $l4statistics = $flowRepository->computeL4ProtoStatistics();
        $l7statistics = $flowRepository->computeL7ProtoStatistics();

        return $this->render('dashboard/index.html.twig', [
            'l4statistics' => $l4statistics,
            'l7statistics' => $l7statistics,
            'flowCount' => $flowCount
        ]);
    }
}
