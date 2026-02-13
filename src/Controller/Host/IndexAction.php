<?php

namespace App\Controller\Host;

use App\Repository\HostRepositoryInterface;
use App\Repository\PaginationQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class IndexAction extends AbstractController {

    #[Route('/host', name: 'hosts')]
    public function __invoke(
        HostRepositoryInterface $hostRepository,
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter(filter: FILTER_DEFAULT, flags: FILTER_FLAG_EMPTY_STRING_NULL | FILTER_NULL_ON_FAILURE)] string|null $ip = null,
    ): Response {
        $hosts = $hostRepository->find(new PaginationQuery(page: $page), $ip);

        return $this->render('hosts/index.html.twig', [
            'hosts' => $hosts,
            'ip' => $ip
        ]);
    }
}
