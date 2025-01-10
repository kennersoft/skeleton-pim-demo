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

class V1Dot0Dot108 extends AbstractMigration
{
    /**
     * @inheritDoc
     */
    public function up(): void
    {
        echo 'Update categories... ' . PHP_EOL;

        $sql = $this
            ->getPDO()
            ->prepare(
                "SELECT id, name, code, owner_user_id, assigned_user_id, name_de_de, name_fr_fr, name_es_es, name_en_gb, name_it_it 
                        FROM category
                        WHERE code IS NULL OR owner_user_id IS NULL OR assigned_user_id IS NULL OR name_de_de IS NULL
                         OR name_fr_fr IS NULL OR name_es_es IS NULL OR name_en_gb IS NULL OR name_it_it IS NULL");
        $sql->execute();

        $categories = $sql->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($categories as $category) {
            $setPart = '';
            $id = $category['id'];
            $name = $category['name'];
            $code = $category['code'];

            foreach (['owner_user_id', 'assigned_user_id'] as $key) {
                if (is_null($category[$key])) {
                    if (strlen($setPart)) {
                        $setPart .= ', ';
                    }
                    $setPart .= $key . '="1"';
                }
            }

            if (is_null($code)) {
                $code = preg_replace('/[^a-z_0-9]/', '', str_replace(' ', '_', strtolower($name)));
                while (!$this->isCodeUnique($code)) {
                    $code .= $this->getSalt();
                }
                $setPart .= 'code="' . $code . '"';
            }

            foreach (['name_de_de', 'name_fr_fr', 'name_es_es', 'name_en_gb', 'name_it_it'] as $key) {
                if (is_null( $category[$key])) {
                    if (strlen($setPart)) {
                        $setPart .= ', ';
                    }
                    $setPart .= $key . '="' . $name . '"';
                }
            }

            if (strlen($setPart)) {
                $sql = $this
                    ->getPDO()
                    ->prepare("UPDATE category SET " . $setPart . " WHERE id='$id'");
                $sql->execute();
            }
        }

        echo 'Done! ' . PHP_EOL;
    }

    /**
     * @param $code
     * @return bool
     */
    public function isCodeUnique($code): bool
    {
        $sql = $this
            ->getPDO()
            ->prepare(
                "SELECT id, name, code
                        FROM category
                        WHERE code='$code'");
        $sql->execute();
        $category = $sql->fetchAll(\PDO::FETCH_ASSOC);

        return count($category) == 0;
    }

    /**
     * @return string
     */
    public function getSalt(): string
    {
        return '_' . substr(md5((string)rand()), 0, 4);
    }

    /**
     * @inheritDoc
     */
    public function down(): void
    {

    }
}
