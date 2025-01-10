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

class V1Dot3Dot60 extends AbstractMigration
{
    /**
     * @inheritDoc
     */
    public function up(): void
    {
        echo 'Fix PC Configurations config ... ' . PHP_EOL;

        file_put_contents('data/logs/_corrupted_configs.log', '');

        (new Auth($this->getContainer()))->useNoAuth();

        $pcConfigurations = $this
            ->getEntityManager()
            ->getRepository('PcConfiguration')
            ->find();

        $totalCorrupted = 0;
        foreach ($pcConfigurations as $pcConfiguration) {
            $configCorrupted = false;
            $configs = $pcConfiguration->get('componentsConfig');
            if (isset($configs) && !empty($configs)) {
                $text = PHP_EOL . "Main Product ID: " . $pcConfiguration->get('mainProductId') . " Config ID: $pcConfiguration->id" . PHP_EOL;
                foreach ($configs as $pfId => $pfConfig) {
                    $forcedCorrupted = $hiddenCorrupted = false;
                    $forcedComponents = $pfConfig->forcedComponents;
                    if (isset($forcedComponents) && !empty($forcedComponents)) {
                        foreach ($forcedComponents as $forcedComponent) {
                            if (!is_object($forcedComponent) && $forcedComponent != '') {
                                $configCorrupted = $forcedCorrupted = true;
                            }
                        }
                    }
                    $hiddenComponents = $pfConfig->hiddenComponents;
                    if (isset($hiddenComponents) && !empty($hiddenComponents) && count($hiddenComponents)) {
                        foreach ($hiddenComponents as $hiddenComponent) {
                            if (!is_string($hiddenComponent) && $hiddenComponent != '') {
                                $configCorrupted = $hiddenCorrupted = true;
                            }
                        }
                    }
                    if ($forcedCorrupted || $hiddenCorrupted) {
                        $text .= "   PF: $pfId";
                        if ($forcedCorrupted) {
                            $text .= " FORCED";
                        }
                        if ($hiddenCorrupted) {
                            $text .= " HIDDEN";
                        }
                        $text .= PHP_EOL;
                    }
                }
                if ($configCorrupted) {
                    $totalCorrupted++;
                    file_put_contents('data/logs/_corrupted_configs.log', $text, FILE_APPEND);
                }
            }
        }

        if ($totalCorrupted > 0) {
            file_put_contents('data/logs/_corrupted_configs.log', PHP_EOL . "TOTAL: $totalCorrupted", FILE_APPEND);
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
