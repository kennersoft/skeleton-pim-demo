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

use Treo\Core\Migration\Base;
use Treo\Core\Utils\Util;

class V1Dot0Dot7 extends Base
{
    /**
     * @inheritDoc
     */
    public function up(): void
    {
        $this->getPDO()->exec(
            "INSERT INTO scheduled_job (id, name, job, status, scheduling) VALUES"
            . " ('" . Util::generateId() . "','Run ITscope import', 'ItScopeImportCron', 'Active', '0 1 * * *')"
        );
    }

    /**
     * @inheritDoc
     */
    public function down(): void
    {
        // delete CoreUpgrade job
        $this->getPDO()->exec("DELETE FROM scheduled_job WHERE job='ItScopeImportCron'");
    }
}
