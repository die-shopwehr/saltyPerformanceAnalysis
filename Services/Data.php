<?php

namespace saltyPerformanceAnalysis\Services;

class Data extends Requirements implements PerformanceDataInterface {

    /**
     * @noinspection ReturnTypeCanBeDeclaredInspection
     * @return array
     */
    public function get()
    {
        return array(
            'unassignedVariants' => $this->checkRequirement('<=', 0, $this->getUnassignedVariants()),
            'duplicateOrderNumbers' => $this->checkRequirement('<=', 0, $this->getDuplicateOrdernumbers()),
            'unassignedFilterValues' => $this->checkRequirement('<=', 0, $this->getUnassignedFilterValues()),
            'defectiveProducts' => $this->checkRequirement('<=', 0, $this->getDefectiveProducts()),
            'missingMainDetails' => $this->checkRequirement('<=', 0, $this->getArticlesWithMissingMainDetail()),
            'duplicateSeoUrls' => $this->checkRequirement('<=', 0, $this->getDuplicateSeoUrls()),
            'oldCancelledBaskets' => $this->checkRequirement('<=', 0, $this->oldCancelledBaskets()),
        );

        // kundenkonten ohne umsatz älter 1 jahr
        //TODO: implement queries by using dbal qb

    }

    /**
     * @return false|string|null
     */
    protected function getUnassignedVariants() {
        $result = Shopware()->Db()->fetchOne('SELECT COUNT(*) as count FROM (
	SELECT d.id
	FROM s_articles_details d
	JOIN s_articles a ON d.articleID = a.id AND a.configurator_set_id IS NOT NULL
	JOIN s_articles_attributes at ON d.id = at.articledetailsID
	LEFT JOIN s_article_configurator_option_relations cr ON cr.article_id = d.id
	WHERE cr.id IS NULL AND d.articleID = a.id

	UNION

	SELECT r.id
	FROM `s_article_configurator_option_relations` r LEFT JOIN s_articles_details d ON d.id = r.article_id WHERE d.id IS NULL

) sub;');

        return $result;
    }

    /**
     * @return false|string|null
     */
    protected function getDuplicateOrdernumbers() {
        $result = Shopware()->Db()->fetchOne('SELECT COUNT(*) FROM (

            SELECT ordernumber, COUNT(ordernumber) AS anzahl
FROM s_order
GROUP BY ordernumber
HAVING ( COUNT(ordernumber) > 1 )
) sub');

        return $result;
    }

    /**
     * @return false|string|null
     */
    protected function getUnassignedFilterValues() {
        $result = Shopware()->Db()->fetchOne('SELECT COUNT(*) FROM `s_filter_values` v LEFT JOIN s_filter_articles a ON a.valueID = v.id
JOIN s_filter_values_attributes va ON v.id = va.valueID
WHERE a.valueID IS NULL;');

        return $result;
    }

    /**
     * @return false|string|null
     */
    protected function getDefectiveProducts() {
        $result = Shopware()->Db()->fetchOne('SELECT COUNT(*)
FROM s_articles a LEFT JOIN s_articles_details ad ON ad.articleID = a.id
WHERE ad.id IS NULL ');

        return $result;
    }

    /**
     * @return false|string|null
     */
    protected function getDuplicateSeoUrls() {
        $result = Shopware()->Db()->fetchOne('SELECT COUNT(*) FROM (
SELECT *
FROM `s_core_rewrite_urls`
WHERE main =1
GROUP BY `org_path` , `main` , `subshopID`, `path`
HAVING COUNT( * ) >1
) sub');

        return $result;
    }

    /**
     * @return false|string|null
     */
    protected function getArticlesWithMissingMainDetail() {
        $result = Shopware()->Db()->fetchOne('SELECT COUNT(a.id) 
FROM s_articles a
LEFT JOIN s_articles_details d ON a.main_detail_id = d.id
WHERE d.id IS NULL;');

        return $result;
    }

    /**
     * @param int $interval
     * @return false|string|null
     */
    protected function oldCancelledBaskets($interval = 90) {
        $result = Shopware()->Db()->fetchOne('SELECT COUNT(id) FROM s_order WHERE ordernumber = 0 AND ordertime < (current_date() - interval ? day);', array($interval));

        return $result;
    }
}