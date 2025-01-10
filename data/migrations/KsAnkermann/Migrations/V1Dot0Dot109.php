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

class V1Dot0Dot109 extends AbstractMigration
{
    /**
     * @inheritDoc
     */
    public function up(): void
    {
        echo 'Update Group Width... ' . PHP_EOL;

        $sqlCases = $this
            ->getPDO()
            ->prepare("SELECT id FROM product where product_family_id='12' AND deleted=0");
        $sqlCases->execute();

        $cases = $sqlCases->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($cases as $case) {
            $caseId = $case['id'];

            $sqlFans = $this
                ->getPDO()
                ->prepare("SELECT * FROM pcc_fanselect where main_product_id='$caseId' AND deleted=0");
            $sqlFans->execute();
            $pccFanselects = $sqlFans->fetchAll(\PDO::FETCH_ASSOC);

            $groups = [];
            foreach ($pccFanselects as $pccFanselect) {
                $groupId = $pccFanselect['placement_fans_case_id'];
                $size = $pccFanselect['size'];
                $values = Json::decode($size);
                $values = is_array($values) ? $values : [];
                if ($groups[$groupId]) {
                    $groups[$groupId]['amounts'][] = count($values);
                    $groups[$groupId]['fans'][$pccFanselect['id']] = $values;
                } else {
                    $groups[$groupId] = [
                        'amounts' => [count($values)],
                        'fans' => [
                            $pccFanselect['id'] => $values
                        ]
                    ];
                }
            }

            foreach ($groups as $groupId => $group) {
                $amounts = $group['amounts'];
                $fans = $group['fans'];
                if (count($amounts) > 1) {
                    $minGroupWidth = 0;
                    $maxGroupWidth = 0;
                    foreach ($fans as $fanId => $fanSizes) {
                        $minGroupWidth += min($fanSizes);
                        $maxGroupWidth += max($fanSizes);
                    }

                    $equalAmounts = (count(array_unique($amounts, SORT_REGULAR)) === 1);
                    $groupWidth = $equalAmounts ? $maxGroupWidth : $minGroupWidth;
                    foreach ($fans as $fanId => $fanSizes) {
                        $sql = $this
                            ->getPDO()
                            ->prepare("UPDATE pcc_fanselect SET group_width = '$groupWidth' WHERE id='$fanId'");
                        $sql->execute();
                    }
                } else {
                    $fanId = array_keys($fans)[0];
                    $groupWidth = max($fans[$fanId] ?: []);
                    $sql = $this
                        ->getPDO()
                        ->prepare("UPDATE pcc_fanselect SET group_width = '$groupWidth' WHERE id='$fanId'");
                    $sql->execute();
                }
            }
        }

        echo 'Done! ' . PHP_EOL;
    }

    /**
     * @param $code
     * @return bool
     */
    public function isCodeUnique($code): bool
    {
        $sql = $this
            ->getPDO()
            ->prepare(
                "SELECT id, name, code
                        FROM category
                        WHERE code='$code'");
        $sql->execute();
        $category = $sql->fetchAll(\PDO::FETCH_ASSOC);

        return count($category) == 0;
    }

    /**
     * @return string
     */
    public function getSalt(): string
    {
        return '_' . substr(md5((string)rand()), 0, 4);
    }

    /**
     * @inheritDoc
     */
    public function down(): void
    {

    }
}
