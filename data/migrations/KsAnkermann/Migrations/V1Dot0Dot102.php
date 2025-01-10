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

class V1Dot0Dot102 extends AbstractMigration
{
    /**
     * @inheritDoc
     */
    public function up(): void
    {
        echo 'Update configurations... ' . PHP_EOL;

        (new Auth($this->getContainer()))->useNoAuth();

        $sql = $this
            ->getPDO()
            ->prepare(
                "SELECT id, name, name_de_de, name_fr_fr, name_es_es, name_en_gb, name_it_it 
                        FROM attribute
                        WHERE name_de_de IS NULL OR name_fr_fr IS NULL OR name_es_es IS NULL OR name_en_gb IS NULL OR name_it_it IS NULL");
        $sql->execute();

        $attributes = $sql->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($attributes as $attribute) {
            $setPart = '';
            $id = $attribute['id'];
            $name = $attribute['name'];

            foreach (['name_de_de', 'name_fr_fr', 'name_es_es', 'name_en_gb', 'name_it_it'] as $key) {
                if (is_null( $attribute[$key])) {
                    if (strlen($setPart)) {
                        $setPart .= ', ';
                    }
                    $setPart .= $key . '="' . $name . '"';
                }
            }

            if (strlen($setPart)) {
                $sql = $this
                    ->getPDO()
                    ->prepare("UPDATE attribute SET " . $setPart . " WHERE id='$id'");
                $sql->execute();
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
