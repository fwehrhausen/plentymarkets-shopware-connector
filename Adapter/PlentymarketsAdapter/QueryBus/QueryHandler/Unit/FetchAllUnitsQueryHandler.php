<?php

namespace PlentymarketsAdapter\QueryBus\QueryHandler\Unit;

use PlentyConnector\Connector\QueryBus\Query\QueryInterface;
use PlentyConnector\Connector\QueryBus\Query\Unit\FetchAllUnitsQuery;
use PlentyConnector\Connector\QueryBus\QueryHandler\QueryHandlerInterface;
use PlentymarketsAdapter\Client\ClientInterface;
use PlentymarketsAdapter\PlentymarketsAdapter;
use PlentymarketsAdapter\ResponseParser\ResponseParserInterface;

/**
 * Class FetchAllUnitsQueryHandler
 */
class FetchAllUnitsQueryHandler implements QueryHandlerInterface
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var ResponseParserInterface
     */
    private $responseParser;

    /**
     * FetchAllUnitsQueryHandler constructor.
     *
     * @param ClientInterface $client
     * @param ResponseParserInterface $responseParser
     */
    public function __construct(
        ClientInterface $client,
        ResponseParserInterface $responseParser
    ) {
        $this->client = $client;
        $this->responseParser = $responseParser;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(QueryInterface $event)
    {
        return $event instanceof FetchAllUnitsQuery &&
            $event->getAdapterName() === PlentymarketsAdapter::getName();
    }

    /**
     * {@inheritdoc}
     */
    public function handle(QueryInterface $event)
    {
        $units = array_map(function ($unit) {
            $names = $this->client->request('GET', 'items/units/' . $unit['id'] . '/names');

            if (!array_key_exists('name', $names)) {
                $names = array_shift($names);
            }

            $unit['name'] = $names['name'];

            return $this->responseParser->parse($unit);
        }, $this->client->request('GET', 'items/units'));

        return array_filter($units);
    }
}