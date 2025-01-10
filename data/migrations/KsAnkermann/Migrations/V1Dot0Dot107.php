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

class V1Dot0Dot107 extends AbstractMigration
{
    /**
     * @inheritDoc
     */
    public function up(): void
    {
        echo 'Updating AmazonFields...' . PHP_EOL;

        $sqlProducts = $this
            ->getPDO()
            ->prepare("SELECT id, 
        amazonbulletpoint, amazonbulletpoint_de_de, amazonbulletpoint_fr_fr, amazonbulletpoint_es_es, amazonbulletpoint_it_it, amazonbulletpoint_en_gb, 
        amazontitleaddon, amazontitleaddon_de_de, amazontitleaddon_fr_fr, amazontitleaddon_es_es, amazontitleaddon_it_it, amazontitleaddon_en_gb, 
        amazongenerickeywords, amazongenerickeywords_de_de, amazongenerickeywords_fr_fr, amazongenerickeywords_es_es, amazongenerickeywords_it_it, amazongenerickeywords_en_gb, 
        amazonvariationaddon , amazonvariationaddon_de_de, amazonvariationaddon_fr_fr, amazonvariationaddon_es_es, amazonvariationaddon_it_it, amazonvariationaddon_en_gb
                             FROM product");
        $sqlProducts->execute();

        $products = $sqlProducts->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($products as $product) {
            $id = $product['id'];
            $locales = ['', '_de_de', '_fr_fr', '_es_es', '_it_it', '_en_gb'];
            $amazonFields = ['amazonbulletpoint', 'amazontitleaddon', 'amazongenerickeywords', 'amazonvariationaddon'];

            $fields = [];
            $values = [];
            $mapper = [
                'id' => Util::generateId(),
                'main_product_id' => $id
            ];
            foreach ($amazonFields as $amazonField) {
                foreach ($locales as $locale) {
                    $fields[] = $amazonField . $locale;
                    $values[] = ':' . $amazonField . $locale;
                    $mapper[$amazonField . $locale] = $product[$amazonField . $locale] ?: null;
                }
            }

            $insert = $this
                ->getPDO()
                ->prepare("INSERT INTO amazon_fields 
                    (id, main_product_id, " . implode(', ', $fields) . ")
                    VALUES (:id, :main_product_id, " . implode(', ', $values) . ")");
            $insert->execute($mapper);
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
