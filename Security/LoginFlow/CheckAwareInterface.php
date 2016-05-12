<?php

namespace MediaMonks\SecurityBundle\Security\LoginFlow;

/**
 * Interface CheckAwareInterface
 * @package MediaMonks\SecurityBundle\Security\LoginFlow
 * @author pawel@mediamonks.com
 */
interface CheckAwareInterface
{
    public function onCheck($event);
}