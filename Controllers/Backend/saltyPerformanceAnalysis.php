<?php
/** @noinspection SpellCheckingInspection */

use saltyPerformanceAnalysis\Services\PerformanceDataInterface;
use Shopware\Components\CSRFWhitelistAware;

class Shopware_Controllers_Backend_saltyPerformanceAnalysis extends Shopware_Controllers_Backend_ExtJs implements CSRFWhitelistAware
{
    /**
     * @var PerformanceDataInterface
     */
    private $configService;

    /**
     * @var PerformanceDataInterface
     */
    private $serverService;

    /**
     * @var PerformanceDataInterface
     */
    private $dataService;

    /**
     * shopware code inspection does not like return type declaration
     * @noinspection ReturnTypeCanBeDeclaredInspection
     * @return array|string[]
     */
    public function getWhitelistedCSRFActions()
    {
        return [
            'index',
            'data'
        ];
    }

    /**
     * constructor requires different number of arguments related to different shopware version.
     */
    public function setValues()
    {
        $this->configService = Shopware()->Container()->get('salty_performance_analysis.services.config');
        $this->serverService = Shopware()->Container()->get('salty_performance_analysis.services.server');
        $this->dataService = Shopware()->Container()->get('salty_performance_analysis.services.data');
    }

    /**
     *
     */
    public function indexAction()
    {
        $this->setValues();

        $data = array(
            'config' => $this->configService->get(),
            'server' => $this->serverService->get(),
            'data' => $this->dataService->get(),
        );

        $this->View()->assign('data', $data);
    }

    /**
     *
     */
    public function dataAction()
    {
        $this->setValues();

        $data = array(
            'config' => $this->configService->get(),
            'server' => $this->serverService->get(),
        );

        if($this->View() === null) {
            return;
        }

        $this->View()->assign(array(
            'success' => true,
            'data' => $data
        ));
    }
}
