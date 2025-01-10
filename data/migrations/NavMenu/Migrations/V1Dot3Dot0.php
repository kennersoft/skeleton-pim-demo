<?php
/**
 * NavMenu
 * Free Extension
 * Copyright (c) TreoLabs GmbH
 * Copyright (c) Kenner Soft Service GmbH
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace NavMenu\Migrations;

use Treo\Core\Migration\AbstractMigration;

/**
 * Version 1.3.0
 *
 */
class V1Dot3Dot0 extends AbstractMigration
{
    /**
     * Set "twoLevelTabList" from "tabList", if "twoLevelTabList" is empty
     */
    public function up(): void
    {
        $config = $this->getConfig();
        $twoLevelTabList = $config->get('twoLevelTabList');
        if (empty($twoLevelTabList)) {
            $config->set('twoLevelTabList', $config->get('tabList'));
            $config->save();
        }
    }
}
