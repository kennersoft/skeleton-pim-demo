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

class V1Dot1Dot70 extends AbstractMigration
{
    /**
     * @inheritDoc
     */
    public function up(): void
    {
        echo 'Clear asset_relation... ' . PHP_EOL;
        $this->getPDO()->exec("delete
            from asset_relation
            where entity_name='Product' and asset_id not in (select id from asset where deleted=0)");

        echo 'Clear product_asset... ' . PHP_EOL;
        $this->getPDO()->exec("delete
            from product_asset
            where deleted=0 and asset_id not in (select asset_id from asset_relation where entity_name='Product' and deleted=0)");

        echo 'Done! ' . PHP_EOL;
    }

    /**
     * @inheritDoc
     */
    public function down(): void
    {
        
    }
}
