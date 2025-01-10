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
use Treo\Core\Utils\Util;

class V1Dot2Dot29 extends AbstractMigration
{
    /**
     * @inheritDoc
     */
    public function up(): void
    {
        echo 'Logging not trimmed array values in data/logs/_array_values.log ... ' . PHP_EOL;

        (new Auth($this->getContainer()))->useNoAuth();

        file_put_contents('data/logs/_array_values.log', "Entities:" . PHP_EOL);
        $scopeList = array_keys($this->getContainer()->get('metadata')->get(['scopes']));
        foreach ($scopeList as $scope) {
            if (in_array($scope, ['Preferences'])) continue;
            if (!$this->getContainer()->get('metadata')->get(['scopes', $scope, 'entity'])) continue;
            if ($this->getContainer()->get('metadata')->get(['scopes', $scope, 'disabled'])) continue;

            $attributeList = [];
            $incorrectOptions = [];
            $fields = $this->getContainer()->get('metadata')->get(['entityDefs', $scope, 'fields'], []);
            foreach ($fields as $attribute => $defs) {
                if (!isset($defs['type']) || !in_array($defs['type'], ['array', 'enum', 'multiEnum']) || $defs['notStorable']) continue;
                $attributeList[] = $attribute;
                $incorrectOptionsTemp = [];
                if (is_array($defs['options'])) {
                    foreach ($defs['options'] as $option) {
                        if (strcmp($option, trim($option)) != 0) {
                            $incorrectOptionsTemp[] = $option;
                        }
                    }
                }
                if (count($incorrectOptionsTemp)) {
                    $incorrectOptions[$attribute] = $incorrectOptionsTemp;
                }
            }
            //log incorrect options
            if (count($incorrectOptions)) {
                file_put_contents('data/logs/_array_values.log', "$scope :" . PHP_EOL, FILE_APPEND);
                foreach ($incorrectOptions as $attribute => $options) {
                    file_put_contents('data/logs/_array_values.log', " - [$attribute]" . PHP_EOL, FILE_APPEND);
                    foreach ($options as $option) {
                        file_put_contents('data/logs/_array_values.log', "   |$option|" . PHP_EOL, FILE_APPEND);
                    }
                }
            }
            //find incorrect values in DB
            if (!count($attributeList)) continue;
            $select = ['id'];
            $orGroup = [];
            foreach ($attributeList as $attribute) {
                $select[] = $attribute;
                $orGroup[$attribute . '!='] = null;
            }
            $sql = $this->getEntityManager()->getQuery()->createSelectQuery($scope, [
                'select' => $select,
                'whereClause' => [
                    'OR' => $orGroup
                ]
            ]);
            $sth = $this->getEntityManager()->getPdo()->prepare($sql);
            $sth->execute();
            $rows = $sth->fetchAll(\PDO::FETCH_ASSOC);
            if (count($rows)) {
                foreach ($rows as $row) {
                    $id = $row['id'];
                    $tableName = Util::toUnderScore($scope);
                    foreach ($attributeList as $attribute) {
                        $value = $row[$attribute];
                        if (empty($value) || !isset($value) || !is_string($value)) continue;
                        if ($fields[$attribute]['type'] != 'enum') {
                            $incorrectValues = [];
                            $newValue = [];
                            $items = Json::decode($value ?: '', true);
                            if (is_array($items)) {
                                foreach ($items as $item) {
                                    if (strcmp($item, trim($item)) != 0) {
                                        $incorrectValues[] = $item;
                                        $newValue[] = trim($item);
                                    } else {
                                        $newValue[] = $item;
                                    }
                                }
                                $newValue = Json::encode($newValue);

                                //log incorrect values
                                if (count($incorrectValues)) {
                                    $text = "$scope ($id) [$attribute] |$value| => |$newValue|" . PHP_EOL;
                                    file_put_contents('data/logs/_array_values.log', $text, FILE_APPEND);

                                    //update row
//                                    $this
//                                        ->getEntityManager()
//                                        ->nativeQuery("UPDATE $tableName SET `$attribute`='$newValue' WHERE id='$id'");
                                }
                            } else {
                                $newValue = null;

                                //log incorrect value
                                $text = "$scope ($id) [$attribute] |$value| => |NULL|" . PHP_EOL;
                                file_put_contents('data/logs/_array_values.log', $text, FILE_APPEND);

                                //update row
//                                $this
//                                    ->getEntityManager()
//                                    ->nativeQuery("UPDATE $tableName SET `$attribute`=NULL WHERE id='$id'");
                            }
                        } else if (strcmp($value, trim($value)) != 0) {
                            $newValue = trim($value);

                            //log incorrect type_values
                            $text = "$scope ($id) [$attribute] |$value| => |$newValue|" . PHP_EOL;
                                file_put_contents('data/logs/_array_values.log', $text, FILE_APPEND);

                            //update row
//                            $this
//                                ->getEntityManager()
//                                ->nativeQuery("UPDATE $tableName SET `$attribute`='$newValue' WHERE id='$id'");
                        }
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
