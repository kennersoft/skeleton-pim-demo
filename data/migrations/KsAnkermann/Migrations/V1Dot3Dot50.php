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

class V1Dot3Dot50 extends AbstractMigration
{
    /**
     * @inheritDoc
     */
    public function up(): void
    {
        echo 'Fix Manually configure fields ... ' . PHP_EOL;

        (new Auth($this->getContainer()))->useNoAuth();

        $pcConfigurations = $this
            ->getEntityManager()
            ->getRepository('Product')
            ->where([
                'type' => 'pcConfigurationProduct'
            ])
            ->find();

        $fixedProducts = [];
        $notFixedProducts = [];
        foreach ($pcConfigurations as $pcConfiguration) {
            $phantom = $pcConfiguration->get('manuallyConfigureFields') ?: [];
            $real = $this
                ->getEntityManager()
                ->nativeQuery("SELECT manually_configure_fields FROM product WHERE id='".$pcConfiguration->id."'")
                ->fetch(\PDO::FETCH_COLUMN);
            if (isset($real) && !empty($real)) {
                $real = Json::decode($real, true);
                if ($phantom != $real) {
                    $pcConfiguration->set(['manuallyConfigureFields' => array_values($real)]);
                    $this->getEntityManager()->saveEntity($pcConfiguration);
                    $fixedProducts[] = $pcConfiguration->id;
                } else {
                    $notFixedProducts[] = $pcConfiguration->id;
                }
            } else {
                $notFixedProducts[] = $pcConfiguration->id;
            }
        }

        file_put_contents('data/logs/migrate_1_3_24_fixed.log', print_r($fixedProducts, true));
        file_put_contents('data/logs/migrate_1_3_24_not_fixed.log', print_r($notFixedProducts, true));

        echo 'Done! ' . PHP_EOL;
    }

    /**
     * @inheritDoc
     */
    public function down(): void
    {

    }
}
