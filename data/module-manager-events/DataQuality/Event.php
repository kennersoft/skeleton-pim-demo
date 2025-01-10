<?php

/**
 * Pim
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

namespace DataQuality;

use Treo\Core\ModuleManager\AbstractEvent;
use Espo\Core\ORM\EntityManager;
use Treo\Core\Utils\Util;

/**
 * @author a.zlatokrylest <a.zlatokrylest@kennersoft.de>
 * @since 2021.05.07
 */
class Event extends AbstractEvent
{
    /**
     * php index.php quality install
     *
     * @inheritdoc
     * @author a.zlatokrylest <a.zlatokrylest@kennersoft.de>
     */
    public function afterInstall(): void
    {
        $this->updateConfig();

        $this->createScheduledJobs();
    }

    /**
     * php index.php quality uninstall
     *
     * @inheritdoc
     * @author a.zlatokrylest <a.zlatokrylest@kennersoft.de>
     */
    public function afterDelete(): void
    {
        $this->dropScheduledJobs();
    }

    protected function updateConfig(): void
    {
        $defaults = include __DIR__ . '/Configs/DataQuality.php';

        $config = $this->getContainer()->get('config');
        $dataQuality = $config->getData()['dataQuality'] ?? null;
        $initParams = (empty($dataQuality) || !is_object($dataQuality))
            ? [] : json_decode(json_encode($dataQuality), true);
        $realParams = [];

        $modified = false;
        foreach ($defaults as $key => $default) {
            if (array_key_exists($key, $initParams)) {
                $realParams[$key] = $initParams[$key];
                unset($initParams[$key]);
            } else {
                $realParams[$key] = $default;
                $modified = true;
            }
        }

        if (count($initParams) > 0) {
            $modified = true;
        }

        if ($modified) {
            $config->set('dataQuality', json_decode(json_encode($realParams)));
            $config->save();
        }
    }

    /**
     * php index.php quality install
     *
     * @see http://localhost:8081/#ScheduledJob
     *
     * @return void
     */
    protected function createScheduledJobs(): void
    {
        /* @var $entityManager EntityManager */
        $entityManager = $this->getContainer()->get('entityManager');

        $jobs = [];
        try {
            $result = $entityManager
                ->nativeQuery("SELECT job FROM scheduled_job WHERE job LIKE 'DataQuality%' AND deleted = 0")
                ->fetchAll(\PDO::FETCH_ASSOC);
            $jobs = array_flip(array_column($result, 'job'));
        } catch (\PDOException $e) {
            $GLOBALS['log']->error('DataQuality: ' . $e->getMessage());
        }

        $now = date('Y-m-d H:i:s');
        $checkers = $this
            ->getContainer()
            ->get('serviceFactory')
            ->create('DataQuality')
            ->getCheckers('full');
        foreach ($checkers as [$job, $name, $scheduling]) {
            if (array_key_exists($job, $jobs)) {
                continue;
            }
            $ceId = Util::generateId();
            $entityManager->nativeQuery(
                <<<CHECKEMPTY
                INSERT INTO scheduled_job (id, name, job, status, scheduling, created_at, created_by_id)
                VALUES ('$ceId', '$name', '$job', 'Active', '$scheduling', '$now', 'system');
                CHECKEMPTY
            );
        }
    }

    /**
     * php index.php quality uninstall
     *
     * @return void
     */
    protected function dropScheduledJobs(): void
    {
        /* @var $entityManager EntityManager */
        $entityManager = $this->getContainer()->get('entityManager');
        $now = date('Y-m-d H:i:s');
        $entityManager->nativeQuery(
            <<<DELETEJOBS
            UPDATE scheduled_job
            SET
                deleted = 1,
                modified_at = '$now',
                modified_by_id = 'system'
            WHERE job LIKE 'DataQuality%';
            DELETEJOBS
        );
    }
}
