<?php
declare(strict_types=1);
namespace Espo\Custom\SelectManagers;

use Pim\SelectManagers\ProductAttributeValue as Base;
use Treo\Core\Utils\Util;

class ProductAttributeValue extends Base
{
    /**
     * @inheritDoc
     */
    public function applyAdditional(array &$result, array $params)
    {
        parent::applyAdditional($result, $params);

        if ($this->isSubQuery) {
            return false;
        }

        $result['additionalSelectColumns']['attribute.tooltip_text'] = 'attributeTooltipText';
        if ($this->getConfig()->get('isMultilangActive')) {
            foreach ($this->getConfig()->get('inputLanguageList', []) as $locale) {
                $localeSuff = ucfirst(Util::toCamelCase(strtolower($locale)));
                $key = 'attribute.tooltip_text_' . strtolower($locale);
                $field = 'attributeTooltipText' . $localeSuff;
                $result['additionalSelectColumns'][$key] = $field;
            }
        }
    }
}
