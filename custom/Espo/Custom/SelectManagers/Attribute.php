<?php
declare(strict_types=1);
namespace Espo\Custom\SelectManagers;

use Pim\SelectManagers\Attribute as PimAttribute;

class Attribute extends PimAttribute
{
    /**
     * @param array $result
     */
    protected function boolFilterLinkedWithProductFamily(array &$result)
    {
        // prepare data
        $data = (array)$this->getSelectCondition('linkedWithProductFamily');

        if (isset($data['productFamilyId'])) {
            $result['whereClause'][] = [
                'id' => $this->getEntityManager()->getRepository('ProductFamily')->getLinkedAttributesIds($data['productFamilyId'])
            ];
        }
    }

    /**
     * @param array $result
     */
    protected function boolFilterLinkedWithIcecatFeature(array &$result)
    {
        // prepare data
        $icecatFeatureId = (array)$this->getSelectCondition('linkedWithIcecatFeature');

        if (isset($icecatFeatureId)) {
            $result['whereClause'][] = [
                'icecatFeatureId' => $icecatFeatureId
            ];
        }
    }
}
