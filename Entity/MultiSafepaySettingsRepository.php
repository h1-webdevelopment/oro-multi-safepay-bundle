<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace H1\OroMultiSafepayBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * MultiSafepaySettingsRepository
 */
class MultiSafepaySettingsRepository extends EntityRepository
{
    /**
     * @return MultiSafepaySettings[]
     */
    public function getEnabledSettings()
    {
        return $this->createQueryBuilder('settings')
            ->innerJoin('settings.channel', 'channel')
            ->andWhere('channel.enabled = true')
            ->getQuery()
            ->getResult();
    }
}
