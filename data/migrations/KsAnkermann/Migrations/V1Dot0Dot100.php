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

class V1Dot0Dot100 extends AbstractMigration
{
    /**
     * @inheritDoc
     */
    public function up(): void
    {
        echo 'Update configurations... ' . PHP_EOL;

        (new Auth($this->getContainer()))->useNoAuth();

        $sqlPc = $this
            ->getPDO()
            ->prepare("SELECT id, main_product_id FROM pc_configuration");
        $sqlPc->execute();

        $pcConfigurations = $sqlPc->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($pcConfigurations as $pcConfiguration) {
            $pcConfigurationId = $pcConfiguration['id'];

            if ($pcConfigurationId) {
                $service = $this->getContainer()->get('serviceFactory')->create('Product');
                $service->updateConfiguration($pcConfiguration['main_product_id']);
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
