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

use Treo\Core\Migration\AbstractMigration;
use Espo\Core\Utils\Json;
use Treo\Core\Utils\Util;

class V1Dot0Dot21 extends AbstractMigration
{
    /**
     * @inheritDoc
     */
    public function up(): void
    {
        echo 'Inserting... ' . PHP_EOL;

        $sqlPc = $this
            ->getPDO()
            ->prepare("SELECT distinct(main_product_id) FROM kss_pc_configuration");
        $sqlPc->execute();
        $mainProductIds = array_column($sqlPc->fetchAll(\PDO::FETCH_ASSOC), 'main_product_id');

        foreach ($mainProductIds as $mainProductId) {
            //prepare Product Families from 'area'
            $mainProduct = $this->getEntityManager()->getRepository('Product')->where(['id' => $mainProductId])->findOne();
            if (empty($mainProduct)) {
                continue;
            }

            $pccarea = $mainProduct->get('pccarea') ?? [];

            $product_family_ids = [];
            $product_family_names = [];
            foreach ($pccarea as $value) {
                $productFamilies = $this->getEntityManager()->getRepository('ProductFamily')->where(['area*' => "%\"$value\"%"])->find();
                foreach ($productFamilies as $productFamily) {
                    $pfId = $productFamily->get('id');
                    if (!in_array($pfId, $product_family_ids)) {
                        $product_family_ids[] = $pfId;
                        $product_family_names[$pfId] = $productFamily->get('name');
                    }
                }
            }

            $new = [
                'id' => Util::generateId(),
                'main_product_id' => $mainProductId,
                'product_family_ids' => Json::encode($product_family_ids),
                'product_family_names' => Json::encode((object)$product_family_names)
            ];
            $componentsConfig = [];

            $sqlMP = $this
                ->getPDO()
                ->prepare("SELECT * FROM kss_pc_configuration where main_product_id='" . $mainProductId . "'");
            $sqlMP->execute();
            $compoments = $sqlMP->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($compoments as $compoment) {
                $pfId = $compoment['product_family_id'];

                $pfConfig = $componentsConfig[$pfId] ?? (object)[
                        'productFamilyDisabled' => false,
                        'componentIds' => [],
                        'hiddenComponents' => [],
                        'forcedComponents' => []
                    ];

                $pfConfig->productFamilyDisabled = $pfConfig->productFamilyDisabled && (int)$compoment['products_hidden_product_families'];

                //get componentIds
                if (!in_array($compoment['component_product_id'], $pfConfig->componentIds)) {
                    $pfConfig->componentIds[] = $compoment['component_product_id'];
                }

                //get hiddenComponents from serialize data
                if (strlen($compoment['products_hidden_components']) > 1) {
                    $doubleQuoted = str_replace("'", '"', $compoment['products_hidden_components']);
                    $unserialized = unserialize($doubleQuoted);
                    if (is_array($unserialized)) {
                        foreach ($unserialized as $id) {
                            if ($id && !in_array($id, $pfConfig->hiddenComponents)) {
                                $pfConfig->hiddenComponents[] = $id;
                            }
                        }
                    }
                }

                //get forcedComponents if '1'
                if ((int)$compoment['products_forced_components'] && !in_array($compoment['component_product_id'], $pfConfig->forcedComponents)) {
                    $pfConfig->forcedComponents[] = $compoment['component_product_id'];
                }

                $componentsConfig[$pfId] = $pfConfig;
            }

            $new['components_config'] = Json::encode((object)$componentsConfig);

            $insert = $this
                ->getPDO()
                ->prepare("INSERT INTO
                    pc_configuration (id, main_product_id, product_family_ids, product_family_names, components_config)
                    VALUES (:id, :main_product_id, :product_family_ids, :product_family_names, :components_config)");
            $insert->execute($new);
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
