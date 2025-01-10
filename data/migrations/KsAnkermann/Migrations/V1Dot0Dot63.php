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

class V1Dot0Dot63 extends AbstractMigration
{
    /**
     * @inheritDoc
     */
    public function up(): void
    {
        echo 'Updating... ' . PHP_EOL;

        $this->getEntityManager()->getPdo()->query("DELETE FROM array_value where entity_type in ('ProductFamily','Product')");

        $scopeList = ['ProductFamily', 'Product'];
        foreach ($scopeList as $scope) {
            if (!$this->getContainer()->get('metadata')->get(['scopes', $scope, 'entity'])) continue;
            if ($this->getContainer()->get('metadata')->get(['scopes', $scope, 'disabled'])) continue;

            $seed = $this->getEntityManager()->getEntity($scope);
            if (!$seed) continue;

            $attributeList = [];

            foreach ($seed->getAttributes() as $attribute => $defs) {
                if (!isset($defs['type']) || $defs['type'] !== \Espo\ORM\Entity::JSON_ARRAY) continue;
                if (!$seed->getAttributeParam($attribute, 'storeArrayValues')) continue;
                if ($seed->getAttributeParam($attribute, 'notStorable')) continue;
                $attributeList[] = $attribute;
            }
            $select = ['id'];
            $orGroup = [];
            foreach ($attributeList as $attribute) {
                $select[] = $attribute;
                $orGroup[$attribute . '!='] = null;
            }

            $sql = $this->getEntityManager()->getQuery()->createSelectQuery($scope, [
                'select' => $select,
                'whereClause' => [
                    'OR' => $orGroup
                ]
            ]);
            $sth = $this->getEntityManager()->getPdo()->prepare($sql);
            $sth->execute();

            while ($dataRow = $sth->fetch(\PDO::FETCH_ASSOC)) {
                $entity = $this->getEntityManager()->getEntityFactory()->create($scope);
                $entity->set($dataRow);
                $entity->setAsFetched();

                foreach ($attributeList as $attribute) {
                    $this->getEntityManager()->getRepository('ArrayValue')->storeEntityAttribute($entity, $attribute, true);
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
