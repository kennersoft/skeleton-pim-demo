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

class V1Dot0Dot99 extends AbstractMigration
{
    /**
     * @inheritDoc
     */
    public function up(): void
    {
        echo 'Updating... ' . PHP_EOL;

        $sqlPc = $this
            ->getPDO()
            ->prepare("SELECT * FROM pcc_fanselect");
        $sqlPc->execute();

        $pccFanselects = $sqlPc->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($pccFanselects as $pccFanselect) {
            $id = $pccFanselect['id'];
            $oldSize = $pccFanselect['size'];

            $arr = Json::decode($oldSize);
            if (is_array($arr)) {
                continue;
            }

            $sizes = explode(',', $oldSize);
            $size = Json::encode($sizes);
            $sqlPc = $this
                ->getPDO()
                ->prepare("UPDATE pcc_fanselect SET size = '$size' WHERE id='$id'");
            $sqlPc->execute();
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
