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
     * Products Required attributes not filled
     *
     * @param $result
     */
    protected function boolFilterNotDisabledInConfiguration(&$result)
    {
        $ids = ['-1'];

        $productsIds = $this
            ->getEntityManager()
            ->nativeQuery("SELECT id FROM product WHERE is_in_pc_configuration_disable!=1 AND deleted = 0")
            ->fetchAll(\PDO::FETCH_ASSOC);
        if (!empty($productsIds)) {
            $ids = array_column($productsIds, 'id');
        }

        $result['whereClause'][] = [
            'id' => $ids
        ];
    }

    /**
     * Products Required attributes not filled
     *
     * @param $result
     */
    protected function boolFilterIncorrectSKUinITscope(&$result)
    {
        $ids = ['-1'];

        $productsIds = $this
            ->getEntityManager()
            ->nativeQuery(
                "SELECT DISTINCT product.id
                FROM product 
                INNER JOIN it_scope on (it_scope.ean = product.ean AND it_scope.puid != product.sku)
                 AND it_scope.ean != '' AND it_scope.ean IS NOT NULL AND it_scope.deleted = 0
                WHERE product.ean != '' AND product.ean IS NOT NULL AND product.deleted = 0")
            ->fetchAll(\PDO::FETCH_ASSOC);

        if (!empty($productsIds)) {
            $ids = array_column($productsIds, 'id');
        }

        $result['whereClause'][] = [
            'id' => $ids
        ];
    }

    /**
     *
     * @param $result
     */
    protected function boolFilterZeroPrice(&$result)
    {
        $ids = ['-1'];

        $productsIds = $this
            ->getEntityManager()
            ->nativeQuery("SELECT id FROM product WHERE deleted=0 AND final_price=0")
            ->fetchAll(\PDO::FETCH_ASSOC);
        if (!empty($productsIds)) {
            $ids = array_column($productsIds, 'id');
        }

        $result['whereClause'][] = [
            'id' => $ids
        ];
    }

    /**
     * Products Required attributes not filled
     *
     * @param $result
     */
    protected function boolFilterByComponentSku(&$result)
    {
        $configurationIds = [];

        $sku = (string)$this->getSelectCondition('byComponentSku');
        $component = $this
            ->getEntityManager()
            ->nativeQuery("SELECT id FROM product WHERE sku='$sku' AND deleted = 0")
            ->fetch(\PDO::FETCH_ASSOC);
        if ($component) {
            $componentId = $component['id'];

            $ids = $this
                ->getEntityManager()
                ->nativeQuery("SELECT main_product_id FROM pc_configuration WHERE components_ids LIKE '%$componentId%'")
                ->fetchAll(\PDO::FETCH_ASSOC);
            $configurationIds = array_column($ids ?: [], 'main_product_id');
        }

        $result['whereClause'][] = [
            'id' => $configurationIds
        ];
    }
    /**
     * Products Required attributes not filled
     *
     * @param $result
     */
    protected function boolFilterNotUsedInTemplates(&$result)
    {
        $ids = ['-1'];

        $productsIds = $this
            ->getEntityManager()
            ->nativeQuery(
                "SELECT id 
                FROM product 
                WHERE deleted=0 AND (entity_template_id IS NULL OR entity_template_id NOT IN (SELECT id FROM entity_template WHERE deleted=0))")
            ->fetchAll(\PDO::FETCH_ASSOC);
        if (!empty($productsIds)) {
            $ids = array_column($productsIds, 'id');
        }

        $result['whereClause'][] = [
            'id' => $ids
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
    protected function boolFilterDataQualityDeactivateComponent(&$result)
    {
        $result['whereClause'][] = [
            'id' => array_column($this->getProductRequiredAttributesNotFilled('DataQualityDeactivateComponent'), 'product_id')
        ];
    }
    protected function boolFilterDataQualityEmptyPcArea(&$result)
    {
        $result['whereClause'][] = [
            'id' => array_column($this->getProductRequiredAttributesNotFilled('DataQualityEmptyPcArea'), 'product_id')
        ];
    }
    protected function boolFilterDataQualityPcAreaComponent(&$result)
    {
        $result['whereClause'][] = [
            'id' => array_column($this->getProductRequiredAttributesNotFilled('DataQualityPcAreaComponent'), 'product_id')
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
    protected function boolFilterDataQualityNotSavedIcecat(&$result)
    {
        $result['whereClause'][] = [
            'id' => array_column($this->getProductRequiredAttributesNotFilled('DataQualityNotSavedIcecat'), 'product_id')
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
