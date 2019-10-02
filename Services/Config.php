<?php
/** @noinspection SpellCheckingInspection */

namespace saltyPerformanceAnalysis\Services;

use Doctrine\ORM\EntityRepository;
use Exception;
use Shopware\Components\Model\ModelManager;
use Shopware\Models\Plugin\Plugin;
use Shopware_Components_Config;

class Config extends Requirements implements PerformanceDataInterface {

    /**
     * @var Shopware_Components_Config
     */
    protected $config;

    /**
     * @var ModelManager
     */
    protected $modelManager;


    /**
     * Config constructor.
     * @param $config
     * @param ModelManager $modelManager
     */
    public function __construct(Shopware_Components_Config $config, ModelManager $modelManager)
    {
        $this->config = $config;
        $this->modelManager = $modelManager;
    }

    /**
     * @noinspection ReturnTypeCanBeDeclaredInspection
     * @return array
     */
    public function get() {
        return array_merge( array(
                'cache' => $this->checkRequirement('=', true, $this->cacheIsActive()),
                'seoRefreshStrategy' => $this->checkRequirement('=', 'Cron', $this->getRefreshStrategy('seoRefreshStrategy')),
                'searchRefreshStrategy' => $this->checkRequirement('=', 'Cron', $this->getRefreshStrategy('searchRefreshStrategy')),
                'similarRefreshStrategy' => $this->checkRequirement('=', 'Cron', $this->getRefreshStrategy('similarRefreshStrategy')),
                'topSellerRefreshStrategy' => $this->checkRequirement('=', 'Cron', $this->getRefreshStrategy('topSellerRefreshStrategy')),
                'pluginsInstalled' => $this->checkRequirement('<=', 15, $this->getPluginStatus(), 20),
            )
        );
    }

    /**
     * @noinspection ReturnTypeCanBeDeclaredInspection
     * @return bool
     */
    protected function cacheIsActive() {
        /** @var EntityRepository $repo */
        $repo = $this->modelManager->getRepository(Plugin::class);

        $httpCache = $repo->findOneBy(['name' => 'HttpCache']);

        return $httpCache && $httpCache->getActive() && $httpCache->getInstalled() !== null;
    }

    /**
     * @noinspection ReturnTypeCanBeDeclaredInspection
     * @param string $name
     * @return string
     */
    protected function getRefreshStrategy(string $name) {
        if($this->config->get($name) === 2) {
            return 'Cron';
        }

        if($this->config->get($name) === 3) {
            return 'Live';
        }

        return '';
    }

    /**
     * @return mixed
     */
    protected function getPluginStatus() {
        $query = $this->modelManager->createQueryBuilder();
        try {
            $result = $query->select('COUNT(plugins.id) as installed')
                ->from(Plugin::class, 'plugins')
                ->where("plugins.source != 'default'")
                ->getQuery()
                ->getSingleScalarResult();
        } catch (Exception $e) {
            $result = array();
        }

        return $result;
    }
}