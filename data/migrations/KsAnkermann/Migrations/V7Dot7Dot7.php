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

class V7Dot7Dot7 extends AbstractMigration
{
    /**
     * @inheritDoc
     */
    public function up(): void
    {
        die(); //do not run this

        echo 'Creating csv... ' . PHP_EOL;

        $sql = $this
            ->getPDO()
            ->prepare("select product.sku as ProductSKU, product.ean as ProductEAN, product_errors.data
                        from product_errors
                        inner join product on product_errors.product_id = product.id
                        where product_errors.data is not null and product_errors.status='Actual'
                          and product_errors.total>0 and product_errors.type='DataQualityRequire'
                          and product.is_active=1 and product.sku is not null");
        $sql->execute();

        $products = $sql->fetchAll(\PDO::FETCH_ASSOC);

        $header = 'ProductSKU,ProductEAN,Attributes';

        $content = $header . PHP_EOL;

        foreach ($products as $product) {
            $data = json_decode($product['data'], true);
            $attrs = [];
            foreach ($data as $key => $info) {
                $id = explode(':', $key)[0];
                $name = $info[1];
                $attrs[] = $id . ':' . $name;
            }
            $attrsData = '"' . implode(', ', $attrs) . '"';

            $content .= $product['ProductSKU'] . ',' . $product['ProductEAN'] . ',' . $attrsData . PHP_EOL;

            file_put_contents('data/pim_missed_attributes.csv', $content);
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
