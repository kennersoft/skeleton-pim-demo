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
use Treo\Core\Migration\AbstractMigration;

class V1Dot3Dot61 extends AbstractMigration
{
    /**
     * @inheritDoc
     */
    public function up(): void
    {
        echo 'Fix Case fans ... ' . PHP_EOL;

        file_put_contents('data/logs/_corrupted_fans.log', '');

        (new Auth($this->getContainer()))->useNoAuth();
        $productService = $this->getContainer()->get('serviceFactory')->create('Product');
        $pcConfigurationService = $this->getContainer()->get('serviceFactory')->create('PcConfiguration');

        $cases = $this
            ->getEntityManager()
            ->getRepository('Product')
            ->select(['id'])
            ->where([
                'productFamilyId' => '12'
            ])
            ->find()
            ->toArray();

        $totalCorrupted = 0;
        foreach ($cases as $case) {
            $caseId = $case['id'];

            $fans = $this
                ->getEntityManager()
                ->nativeQuery("SELECT * FROM `pcc_fanselect` where `main_product_id`='$caseId' AND `deleted`=0 ORDER BY `placement_fans_case_id`, `order`")
                ->fetchAll(\PDO::FETCH_ASSOC);
            if (count($fans)) {
                $forUpdate = [];
                $i = 1;
                foreach ($fans as $fan) {
                    if ($fan['order'] != $i) {
                        $forUpdate[] = [
                            'id' => $fan['id'],
                            'order' => $i
                        ];
                    }
                    $i++;
                }
                if (count($forUpdate)) {
                    $sql = '';
                    foreach ($forUpdate as $fan) {
                        $id = $fan['id'];
                        $order = $fan['order'];
                        $sql .= "UPDATE pcc_fanselect SET `order`='$order' WHERE `id`='$id';";
                    }
                    try {
                        $totalCorrupted++;
                        file_put_contents('data/logs/_corrupted_fans.log', PHP_EOL . "Case ID: $caseId" . PHP_EOL . "   SQL: $sql" . PHP_EOL, FILE_APPEND);
                        $this->getEntityManager()->nativeQuery($sql);
                        //update PC Configurations
                        $pcConfigurations = $productService->getPcConfigurationsWithComponent($caseId);
                        foreach ($pcConfigurations as $config) {
                            $pcConfiguration = $this->getEntityManager()->getEntity('PcConfiguration', $config['id']);
                            if ($pcConfiguration && $pcConfiguration->get('mainProductId')) {
                                $pcConfigurationService->updateFansFromCase($pcConfiguration, true);
                            }
                        }
                    } catch (\Throwable $e) {
                        $GLOBALS['log']->error('BadRequest: ' . $e->getMessage());
                    }
                }
            }
        }

        if ($totalCorrupted > 0) {
            file_put_contents('data/logs/_corrupted_fans.log', PHP_EOL . "TOTAL: $totalCorrupted", FILE_APPEND);
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
