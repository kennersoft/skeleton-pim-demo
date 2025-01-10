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

class V1Dot2Dot34 extends AbstractMigration
{
    /**
     * @inheritDoc
     */
    public function up(): void
    {
        echo 'Update calculator data existence... ' . PHP_EOL;

        (new Auth($this->getContainer()))->useNoAuth();
        //clear previous data
        $this->getEntityManager()->nativeQuery("update product set calculator_data = 0");

        $sql = $this
            ->getPDO()
            ->prepare("SELECT id, main_product_id, calculator_data FROM pc_configuration WHERE deleted=0");
        $sql->execute();
        $items = $sql->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($items as $item) {
            $productId = $item['main_product_id'];
            $data = $item['calculator_data'];
            $exist = 0;
            if (!empty($data) && is_string($data)) {
                $data = Json::decode($data, true);
                $exist = (int) (!empty($data) && $data['sum1'] > 0);
            }
            $this
                ->getEntityManager()
                ->nativeQuery("UPDATE product SET calculator_data=$exist WHERE id='$productId'");
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
