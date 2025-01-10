<?php
/*
 * Ks Ankermann
 * Premium Plugin
 * Copyright (c) Kenner Soft Service GmbH
 * Website: https://kennersoft.de
 *
 * This Software is the property of Kenner Soft Service GmbH and is protected
 *  by copyright law - it is NOT Freeware and can be used only in one project
 * under a proprietary license, which is delivered along with this program.
 * If not, see <https://kennersoft.de/eula>.
 *
 * This Software is distributed as is, with LIMITED WARRANTY AND LIABILITY.
 * Any unauthorised use of this Software without a valid license is
 * a violation of the License Agreement.
 *
 * According to the terms of the license you shall not resell, sublicense,
 * rent, lease, distribute or otherwise transfer rights or usage of this
 * Software or its derivatives. You may modify the code of this Software
 * for your own needs, if source code is provided.
 */

declare(strict_types=1);

namespace KsAnkermann\Migrations;

use Espo\Core\Utils\Auth;
use Espo\Core\Utils\Json;
use Treo\Core\Migration\AbstractMigration;

class V1Dot2Dot28 extends AbstractMigration
{
    /**
     * @inheritDoc
     */
    public function up(): void
    {
        echo 'Logging not trimmed Attribute TypeValues in data/logs/_attribute_values.log ... ' . PHP_EOL;

        (new Auth($this->getContainer()))->useNoAuth();

        $attributes = $this
            ->getEntityManager()
            ->nativeQuery("SELECT `id`, `name`, `type`, `type_value` 
                FROM `attribute` WHERE `deleted`=0 AND `type` IN ('array','enum','multiEnum')")
            ->fetchAll(\PDO::FETCH_ASSOC);

        file_put_contents('data/logs/_attribute_values.log', 'Attributes: ' . PHP_EOL);

        foreach ($attributes as $attribute) {
            $attributeId = $attribute['id'];
            $name = $attribute['name'];
            $newTypeValue = [];
            $incorrectTypeValues = [];
            if (empty($attribute['type_value']) || !isset($attribute['type_value']) || !is_string($attribute['type_value'])
                || !is_array($typeValues = Json::decode($attribute['type_value'] ?: '', true))) {
                continue;
            }
            foreach ($typeValues as $value) {
                if (strcmp($value, trim($value)) != 0) {
                    $incorrectTypeValues[] = $value;
                    $newTypeValue[] = trim($value);
                } else {
                    $newTypeValue[] = $value;
                }
            }
            //log incorrect type_values
            if (count($incorrectTypeValues)) {
                file_put_contents('data/logs/_attribute_values.log', "($attributeId) $name :" . PHP_EOL, FILE_APPEND);
                foreach ($incorrectTypeValues as $typeValue) {
                    file_put_contents('data/logs/_attribute_values.log', "   $typeValue" . PHP_EOL, FILE_APPEND);
                }
            }

            //update attribute
            if (!empty(array_diff($typeValues, $newTypeValue)) || !empty(array_diff($newTypeValue, $typeValues))) {
//                $this
//                    ->getEntityManager()
//                    ->nativeQuery("UPDATE `attribute` SET `type_value`=:type_value WHERE id='$attributeId'",
//                        ['type_value' => Json::encode($newTypeValue)]);
            }

            //find incorrect values in ProductAttributeValue table
            $pavs = $this
                ->getEntityManager()
                ->nativeQuery("SELECT `id`, `value`
                    FROM `product_attribute_value` WHERE `value` IS NOT NULL AND `deleted`=0 AND `attribute_id`='$attributeId'")
                ->fetchAll(\PDO::FETCH_ASSOC);
            if (count($pavs)) {
                foreach ($pavs as $pav) {
                    $id = $pav['id'];
                    $newValues = [];
                    $incorrectValues = [];
                    if (empty($pav['value']) || !isset($pav['value']) || !is_string($pav['value'])
                        || !is_array($values = Json::decode($pav['value'] ?: '', true))) {
                        continue;
                    }
                    foreach ($values as $value) {
                        if (strcmp($value, trim($value)) != 0) {
                            $incorrectValues[] = $value;
                            $newValues[] = trim($value);
                        } else {
                            $newValues[] = $value;
                        }
                    }

                    //log incorrect values
                    if (count($incorrectValues)) {
                        file_put_contents('data/logs/_attribute_values.log', "      PAVs:" . PHP_EOL, FILE_APPEND);
                        file_put_contents('data/logs/_attribute_values.log', "      ($id)" . PHP_EOL, FILE_APPEND);
                        foreach ($incorrectValues as $value) {
                            file_put_contents('data/logs/_attribute_values.log', "     $value" . PHP_EOL, FILE_APPEND);
                        }
                    }

                    //update ProductAttributeValue
                    if (!empty(array_diff($values, $newValues)) || !empty(array_diff($newValues, $values))) {
                        $value = Json::encode($newValues);
//                        $this
//                            ->getEntityManager()
//                            ->nativeQuery("UPDATE product_attribute_value SET `value`='$value' WHERE id='$id'");
                    }
                }
            }
        }

        echo 'Done! ' . PHP_EOL;
    }

    /**
     * @inheritDoc
     */
    public function down(): void
    {
        
    }
}
