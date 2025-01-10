<?php
/**
 * Dam
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

namespace Dam\Migrations;

use Treo\Core\Migration\AbstractMigration;

class V1Dot2Dot10 extends AbstractMigration
{
    /**
     * @inheritdoc
     */
    public function up(): void
    {
        $this->exec("ALTER TABLE asset ADD INDEX is_active (is_active)");
        $this->exec("ALTER TABLE asset ADD INDEX deleted (deleted)");
        $this->exec("ALTER TABLE asset ADD INDEX file_id (file_id)");

        $this->exec("ALTER TABLE attachment ADD INDEX deleted (deleted)");
    }
    
    /**
     * @inheritdoc
     */
    public function down(): void
    {
    }
}
