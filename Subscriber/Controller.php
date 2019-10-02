<?php

namespace saltyPerformanceAnalysis\Subscriber;

use Enlight\Event\SubscriberInterface;

class Controller implements SubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            'Enlight_Controller_Dispatcher_ControllerPath_Backend_saltyPerformanceAnalysis' => 'getBackendController'
        );
    }

    /**
     * Register the backend widget controller
     *
     * @param   \Enlight_Event_EventArgs $args
     * @return  string
     */
    public function getBackendController(\Enlight_Event_EventArgs $args)
    {
        return __DIR__ . '/../Controllers/Backend/saltyPerformanceAnalysis.php';
    }
}
