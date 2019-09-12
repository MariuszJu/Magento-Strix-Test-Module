<?php

namespace RockSolidSoftware\BookRental\Cron;

use Zend\Log\Logger;
use \Zend\Log\Writer\Stream;

class TestCron
{

    /**
     * @return void
     */
    public function execute(): void
    {
        (new Logger())
            ->addWriter(new Stream(BP . '/var/log/cron.log'))
            ->info('TEST! : ' . (new \DateTime())->format('Y-m-d H:i:s'));
    }

}
