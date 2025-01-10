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

class V1Dot3Dot65 extends AbstractMigration
{
    /**
     * @inheritDoc
     */
    public function up(): void
    {
        echo 'Update Final Price for components ... ' . PHP_EOL;

        (new Auth($this->getContainer()))->useNoAuth();
        $productService = $this->getContainer()->get('serviceFactory')->create('Product');
        $priceService = $this->getContainer()->get('serviceFactory')->create('UpdateProductPrice');

        $updateComponents = [];
        $updateConfigurations = [];
        $components = $this
            ->getEntityManager()
            ->getRepository('Product')
            ->where([
                'productFamilyId!=' => ['61c1c99b35c72804b', '64464d258e5e277ef', '618d0ff4e84ad76fd', '619e47ff66aca305f']
            ])
            ->find();

        foreach ($components as $component) {
            $usedPrice = $component->get('usedPrice');
            if ($usedPrice == 'weclappPrice') {
                $oldFinalPrice = $component->get('finalPrice');
                $finalPrice = $priceService->updateProductFinalPrice($component);
                if ($oldFinalPrice !== $finalPrice) {
                    $this->getEntityManager()->saveEntity($component, ['skipAfterSave' => true]);
                    $updateComponents[] = $component->id;
                    $pcConfigurations = $productService->getPcConfigurationsWithComponent($component->id);
                    if (is_array($pcConfigurations) && count($pcConfigurations)) {
                        foreach ($pcConfigurations as $pcConfiguration) {
                            if (!in_array($pcConfigurations['id'], $updateConfigurations)) {
                                $updateConfigurations[] = $pcConfiguration['id'];
                            }
                        }
                    }
                }
            }
        }

        if (count($updateConfigurations)) {
            $name = $this->getContainer()->get('language')->translate('Updating PC Configurations', 'labels', 'Product');

            try {
                $this
                    ->getContainer()
                    ->get('queueManager')
                    ->push($name, 'QueueManagerConfigurationUpdate', [
                        'configurationIds' => $updateConfigurations,
                        'updatingFields' => ['prices']
                    ]);
            } catch (\Throwable $e) {
                $GLOBALS['log']->error('Weclapp update PC Configurations: ' . $e->getMessage());
            }
        }

        file_put_contents('data/logs/migrate_1_3_65.log', print_r($updateComponents, true));

        echo 'Done! ' . PHP_EOL;
    }

    /**
     * @inheritDoc
     */
    public function down(): void
    {

    }
}
