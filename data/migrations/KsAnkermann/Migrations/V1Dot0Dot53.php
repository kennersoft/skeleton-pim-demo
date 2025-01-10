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

class V1Dot0Dot53 extends AbstractMigration
{
    /**
     * @inheritDoc
     */
    public function up(): void
    {
        echo 'Updating... ' . PHP_EOL;

        $sqlPc = $this
            ->getPDO()
            ->prepare("SELECT id, components_config FROM pc_configuration");
        $sqlPc->execute();

        $pcConfigurations = $sqlPc->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($pcConfigurations as $pcConfiguration) {
            $componentsIds = [];
            $id = $pcConfiguration['id'];
            $config = $pcConfiguration['components_config'];

            if ($config && is_string($config)) {
                $pfConfigs = Json::decode($config);

                if (!is_object($pfConfigs)) {
                    continue;
                }

                foreach ($pfConfigs as $pfId => $pfConfig) {
                    if (!$pfConfig->components) {
                        continue;
                    }

                    $componentsIds = array_merge($componentsIds, array_keys(get_object_vars($pfConfig->components)));
                }
            }

            if (count($componentsIds)) {
                $componentsIds = Json::encode($componentsIds);
                try {
                $insert = $this
                    ->getPDO()
                    ->prepare("UPDATE pc_configuration SET components_ids='$componentsIds' WHERE id='$id'");
                $insert->execute();
                } catch (\Throwable $e) {
                    $GLOBALS['log']->error('Migrate 1.0.53 ERROR: ' . $e->getMessage());
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
