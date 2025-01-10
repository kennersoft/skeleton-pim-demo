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

class V1Dot3Dot55 extends AbstractMigration
{
    /**
     * @inheritDoc
     */
    public function up(): void
    {
        echo 'Create Job ... ' . PHP_EOL;

        (new Auth($this->getContainer()))->useNoAuth();

        $pcConfigurations = $this
            ->getEntityManager()
            ->getRepository('PcConfiguration')
            ->select(['id'])
            ->find()
            ->toArray();

        if (count($pcConfigurations)) {
            $name = $this->getContainer()->get('language')->translate('Updating PC Configurations', 'labels', 'Product');

            $this
                ->getContainer()
                ->get('queueManager')
                ->push($name, 'QueueManagerConfigurationUpdate', [
                    'configurationIds' => array_column($pcConfigurations, 'id'),
                    'updatingFields' => ['prices']
                ]);
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
