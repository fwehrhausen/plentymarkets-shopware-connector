<?php

namespace ShopwareAdapter\ResponseParser\CustomerGroup;

use PlentyConnector\Connector\TransferObject\CustomerGroup\CustomerGroup;

/**
 * Interface CustomerGroupResponseParserInterface
 */
interface CustomerGroupResponseParserInterface
{
    /**
     * @param array $entry
     *
     * @return null|CustomerGroup
     */
    public function parse(array $entry);
}
