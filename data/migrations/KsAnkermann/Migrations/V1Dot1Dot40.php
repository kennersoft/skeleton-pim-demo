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

class V1Dot1Dot40 extends AbstractMigration
{
    /**
     * @inheritDoc
     */
    public function up(): void
    {
        echo 'Update image from main asset in product... ' . PHP_EOL;

        (new Auth($this->getContainer()))->useNoAuth();

        $sqlPc = $this
            ->getPDO()
            ->prepare("SELECT id, image_id
                            FROM product
                            WHERE deleted=0 and type='pcConfigurationProduct' and image_id is null");
        $sqlPc->execute();

        $products = $sqlPc->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($products as $product) {
            $productId = $product['id'];
            $mainAssetRelation = $this
                ->getEntityManager()
                ->getRepository('AssetRelation')
                ->where([
                    'entityId' => $productId,
                    'role' => '["Main"]',
                    'deleted' => 0
                ])
                ->findOne();
            if ($mainAssetRelation && $mainAssetRelation->get('assetId')) {
                $mainAsset = $this->getEntityManager()->getEntity('Asset', $mainAssetRelation->get('assetId'));
                if ($mainAsset && $mainAsset->get('fileId')) {
                    $attachment = $this->getEntityManager()->getEntity('Attachment', $mainAsset->get('fileId'));
                    if ($attachment) {
                        $this->getEntityManager()
                            ->nativeQuery("UPDATE product SET image_id= :imageId WHERE id = :id ",
                                [
                                    'imageId' => $mainAsset->get('fileId'),
                                    'id' => $productId
                                ]);
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
