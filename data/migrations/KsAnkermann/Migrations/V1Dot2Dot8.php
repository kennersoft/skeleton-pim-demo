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

class V1Dot2Dot8 extends AbstractMigration
{
    /**
     * @inheritDoc
     */
    public function up(): void
    {
        echo 'Update calculator data... ' . PHP_EOL;

        (new Auth($this->getContainer()))->useNoAuth();

        $sqlPc = $this
            ->getPDO()
            ->prepare("SELECT id, calculator_data
                            FROM product
                            WHERE deleted=0 and type='pcConfigurationProduct'");
        $sqlPc->execute();
        $items = $sqlPc->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($items as $item) {
            $productId = $item['id'];
            $data = $item['calculator_data'];

            $sql = $this
                ->getPDO()
                ->prepare("UPDATE pc_configuration SET calculator_data='$data' WHERE main_product_id='$productId'");
            $sql->execute();
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
