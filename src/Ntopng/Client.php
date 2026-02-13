<?php

namespace App\Ntopng;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class Client {
    public function __construct(private HttpClientInterface $ntopngClient, private SerializerInterface $serializer) {

    }

    public function getInterfaces(): InterfacesResponse {
        $json = $this->ntopngClient->request('GET', '/lua/rest/v2/get/ntopng/interfaces.lua');
        return $this->serializer->deserialize($json->getContent(), InterfacesResponse::class, 'json');
    }

    public function getFlows(int $interfaceId, int $page = 1, int $perPage = 100): ActiveFlowsResponse {
        $json = $this->ntopngClient->request('GET', '/lua/rest/v2/get/flow/active.lua?ifid=' . $interfaceId . '&currentPage=' . $page . '&perPage=' . $perPage);
        return $this->serializer->deserialize($json->getContent(), ActiveFlowsResponse::class, 'json');
    }

    public function getActiveFlowsList(int $interfaceId, int $start = 0, int $length = 10000): ActiveFlowListResponse {
        $json = $this->ntopngClient->request('GET', '/lua/rest/v2/get/flow/active_list.lua?ifid=' . $interfaceId . '&start=' . $start . '&length=' . $length);
        return $this->serializer->deserialize($json->getContent(), ActiveFlowListResponse::class, 'json');
    }

    public function getLocalHosts(int $interfaceId, int $page = 1, int $perPage = 100): ActiveHostsResponse {
        $json = $this->ntopngClient->request('GET', '/lua/rest/v2/get/host/active.lua?ifid=' . $interfaceId . '&mode=local');
        return $this->serializer->deserialize($json->getContent(), ActiveHostsResponse::class, 'json');
    }
}
