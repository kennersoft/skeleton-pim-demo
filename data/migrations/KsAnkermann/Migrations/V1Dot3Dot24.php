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
use Treo\Core\Utils\Util;

class V1Dot3Dot24 extends AbstractMigration
{
    /**
     * @inheritDoc
     */
    public function up(): void
    {
        echo 'Update Manually configure fields ... ' . PHP_EOL;

        (new Auth($this->getContainer()))->useNoAuth();

        $pcConfigurations = $this
            ->getEntityManager()
            ->getRepository('Product')
            ->where([
                'type' => 'pcConfigurationProduct'
            ])
            ->find();

        $baseCheckFields = ['amazonBulletPointList1Manual', 'amazonBulletPointList2Manual', 'amazonBulletPointList3Manual',
            'amazonBulletPointList4Manual', 'amazonBulletPointList5Manual'];
        $checkFields = [];
        foreach ($baseCheckFields as $field) {
            $checkFields[] = $field;
            if ($this->getConfig()->get('isMultilangActive')) {
                foreach ($this->getConfig()->get('inputLanguageList') as $locale) {
                    $locale = ucfirst(Util::toCamelCase(strtolower($locale)));
                    $checkFields[] = $field . $locale;
                }
            }
        }
        $updatedProducts = [];
        foreach ($pcConfigurations as $pcConfiguration) {
            $amazonFields = $this
                ->getEntityManager()
                ->getRepository('AmazonFields')
                ->where(['mainProductId' => $pcConfiguration->id])
                ->findOne();
            if (!$amazonFields) {
                continue;
            }
            $manuallyConfigureFields = $pcConfiguration->get('manuallyConfigureFields') ?: [];
            $base = $pcConfiguration->get('manuallyConfigureFields') ?: [];
            $update = false;
            foreach ($base as $key => $value) {
                if (in_array($value, $checkFields) && empty($amazonFields->get($value))) {
                    unset($manuallyConfigureFields[$key]);
                    $update = true;
                }
            }
            if ($update) {
                $pcConfiguration->set(['manuallyConfigureFields' => array_values($manuallyConfigureFields)]);
                $this->getEntityManager()->saveEntity($pcConfiguration);
                $updatedProducts[] = $pcConfiguration->id;
            }
        }

        file_put_contents('data/logs/migrate_1_3_24.log', print_r($updatedProducts, true));

        echo 'Done! ' . PHP_EOL;
    }

    /**
     * @inheritDoc
     */
    public function down(): void
    {

    }
}
