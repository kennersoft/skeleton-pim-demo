<?php
declare(strict_types=1);
namespace Espo\Custom\SelectManagers;

use Pim\SelectManagers\Product as PimProduct;

class Product extends PimProduct
{
    /**
     * Products Required attributes not filled
     *
     * @param $result
     */
    protected function boolFilterRequiredAttributesNotFilled(&$result)
    {
        $result['whereClause'][] = [
            'id' => array_column($this->getProductRequiredAttributesNotFilled('DataQualityRequire'), 'product_id')
        ];
    }

    /**
     * @param $resultyv
     */
    protected function boolFilterDataQualityImageCount(&$result)
    {
        $result['whereClause'][] = [
            'id' => array_column($this->getProductRequiredAttributesNotFilled('DataQualityImageCount'), 'product_id')
        ];
    }

    /**
     * Products DuplicateMpn
     *
     * @param $result
     */
    protected function boolFilterDuplicateMpn(&$result)
    {
        $result['whereClause'][] = [
            'id' => array_column($this->getProductRequiredAttributesNotFilled('DataQualityDupMpn'), 'product_id')
        ];
    }

    /**
     * Products DataQualityDupTitle
     *
     * @param $result
     */
    protected function boolFilterDuplicateTitle(&$result)
    {
        $result['whereClause'][] = [
            'id' => array_column($this->getProductRequiredAttributesNotFilled('DataQualityDupTitle'), 'product_id')
        ];
    }

    /**
     * Products DataQualityDupTitle
     *
     * @param $result
     */
    protected function boolFilterDuplicateSku(&$result)
    {
        $result['whereClause'][] = [
            'id' => array_column($this->getProductRequiredAttributesNotFilled('DataQualityDupSku'), 'product_id')
        ];
    }

    protected function boolFilterDuplicateEan(&$result)
    {
        $result['whereClause'][] = [
            'id' => array_column($this->getProductRequiredAttributesNotFilled('DataQualityDupEan'), 'product_id')
        ];
    }

    protected function boolFilterDataQualitySpell(&$result)
    {
        $result['whereClause'][] = [
            'id' => array_column($this->getProductRequiredAttributesNotFilled('DataQualitySpell'), 'product_id')
        ];
    }

    protected function boolFilterDataQualityReqSku(&$result)
    {
        $result['whereClause'][] = [
            'id' => array_column($this->getProductRequiredAttributesNotFilled('DataQualityReqSku'), 'product_id')
        ];
    }

    protected function boolFilterDataQualityReqEan(&$result)
    {
        $result['whereClause'][] = [
            'id' => array_column($this->getProductRequiredAttributesNotFilled('DataQualityReqEan'), 'product_id')
        ];
    }

    protected function boolFilterDataQualityReqMpn(&$result)
    {
        $result['whereClause'][] = [
            'id' => array_column($this->getProductRequiredAttributesNotFilled('DataQualityReqMpn'), 'product_id')
        ];
    }

    protected function boolFilterDataQualityImageLow(&$result)
    {
        $result['whereClause'][] = [
            'id' => array_column($this->getProductRequiredAttributesNotFilled('DataQualityImageLow'), 'product_id')
        ];
    }

    protected function boolFilterDataQualityImageHigh(&$result)
    {
        $result['whereClause'][] = [
            'id' => array_column($this->getProductRequiredAttributesNotFilled('DataQualityImageHigh'), 'product_id')
        ];
    }

    protected function boolFilterDataQualityImageRatio(&$result)
    {
        $result['whereClause'][] = [
            'id' => array_column($this->getProductRequiredAttributesNotFilled('DataQualityImageRatio'), 'product_id')
        ];
    }

    protected function boolFilterDataQualityImageType(&$result)
    {
        $result['whereClause'][] = [
            'id' => array_column($this->getProductRequiredAttributesNotFilled('DataQualityImageType'), 'product_id')
        ];
    }

    protected function boolFilterDataQualityImageBig(&$result)
    {
        $result['whereClause'][] = [
            'id' => array_column($this->getProductRequiredAttributesNotFilled('DataQualityImageBig'), 'product_id')
        ];
    }

    protected function boolFilterDataQualityImageSmall(&$result)
    {
        $result['whereClause'][] = [
            'id' => array_column($this->getProductRequiredAttributesNotFilled('DataQualityImageSmall'), 'product_id')
        ];
    }

    protected function boolFilterDataQualityChannel(&$result)
    {
        $result['whereClause'][] = [
            'id' => array_column($this->getProductRequiredAttributesNotFilled('DataQualityChannel'), 'product_id')
        ];
    }

    /**
     * Get products required attributes not filled
     *
     * @return array
     */
    protected function getProductRequiredAttributesNotFilled($type): array
    {
        $query =
             "SELECT product_id 
                FROM product_errors as pe 
                WHERE 
                pe.`type` = '$type' 
                  AND 
                deleted = 0
                  AND
                status = 'Actual'
                  AND 
                count1 > 0 
                ";


        $sth = $this->getEntityManager()->getPDO()->prepare($query);
        $sth->execute();

        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }

}
