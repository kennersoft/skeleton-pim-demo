<?php

declare(strict_types=1);

namespace Espo\Custom\SelectManagers;

use Pim\SelectManagers\ProductAttributeValue as Base;

class ProductAttributeValue extends Base
{
    /**
     * @inheritDoc
     */
    public function applyAdditional(array &$result, array $params)
    {
        parent::applyAdditional($result, $params);
    }
}
