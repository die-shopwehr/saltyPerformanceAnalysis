<?php
/** @noinspection SpellCheckingInspection */

namespace saltyPerformanceAnalysis\Services;

use Shopware\Components\MemoryLimit;

class Server extends Requirements implements PerformanceDataInterface {


    /**
     * @noinspection ReturnTypeCanBeDeclaredInspection
     * @return array
     */
    public function get() {
        $result = array(
            'memoryLimit' => $this->checkRequirement('>=', 512, $this->getMemoryLimitInMb(), 256),
            'cdn' => $this->checkRequirement('=', true, $this->cdnIsActive()),
            'sessionHandlerIsSet' => $this->checkRequirement('=', true, $this->sessionHandlerIsSet()),
            'apcu' => $this->checkRequirement('=', true, $this->isApcuEnabled()),
            'opcache' => $this->checkRequirement('=', true, $this->isOpcacheEnabled()),
            'serverProtocol' => $this->checkRequirement('>=', 2, $this->getProtocolVersion(), 1.1),
            'phpVersion' => $this->checkRequirement('v+', '7.2', explode("-",PHP_VERSION)[0], '7.1')
        );

        if(Shopware()->Container()->hasParameter('shopware.es.enabled'))
        {
            $result = array_merge($result, array('es' => $this->checkRequirement('=', true, $this->isEsEnabled(), '', true)));
        }

        return $result;
    }

    /**
     * @noinspection ReturnTypeCanBeDeclaredInspection
     * @return int
     */
    protected function getMemoryLimitInMb() {
        return (int)(MemoryLimit::convertToBytes(@ini_get('memory_limit')) / 1024 / 1024);
    }

    /**
     * @noinspection ReturnTypeCanBeDeclaredInspection
     * @return bool
     */
    protected function sessionHandlerIsSet()
    {
        $sessionOptions = Shopware()->Container()->getParameter('shopware.session');
        return isset($sessionOptions['save_handler']) && $sessionOptions['save_handler'] !== 'db';
    }

    /**
     * @noinspection ReturnTypeCanBeDeclaredInspection
     * @return bool
     */
    protected function isEsEnabled()
    {
        $esOptions = Shopware()->Container()->getParameter('shopware.es.enabled');
        return isset($esOptions) && $esOptions === true;
    }

    /**
     * @noinspection ReturnTypeCanBeDeclaredInspection
     * @return bool
     */
    protected function isApcuEnabled()
    {
        return extension_loaded('apcu');
    }

    /**
     * @noinspection ReturnTypeCanBeDeclaredInspection
     * @return bool
     */
    protected function isOpcacheEnabled()
    {
        return extension_loaded('Zend OPcache');
    }

    /**
     * @noinspection ReturnTypeCanBeDeclaredInspection
     * @return string
     */
    private function getProtocolVersion() {
        $matches = [];

        preg_match_all('/\/(\d+)?.?(\d+)?.?/m', $_SERVER['SERVER_PROTOCOL'], $matches, PREG_SET_ORDER);

        unset($matches[0][0]);
        $version = implode('.', $matches[0]);

        if($this->validateVersion($version)) {
            return $version;
        }

        return '';
    }

    /**
     * @noinspection ReturnTypeCanBeDeclaredInspection
     * @param string $version
     * @return bool
     */
    private function validateVersion(string $version)
    {
        return version_compare($version, '0.0.1', '>=') >= 0;
    }

    /**
     * @noinspection ReturnTypeCanBeDeclaredInspection
     * @return bool
     */
    protected function cdnIsActive()
    {
        //todo: inject
        $cdnConfig = Shopware()->Container()->getParameter('shopware.cdn');

        if($cdnConfig['adapters']['local']['mediaUrl']) {
            return true;
        }

        if($cdnConfig['backend'] !== 'local') {
            return true;
        }

        return false;
    }
}