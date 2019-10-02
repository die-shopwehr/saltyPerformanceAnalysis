<?php
//declare(strict_types=1);
/** @noinspection SpellCheckingInspection */

namespace saltyPerformanceAnalysis\Services;

class Requirements
{
    /**
     * @noinspection ReturnTypeCanBeDeclaredInspection
     * @param string $condition
     * @param $recommendation
     * @param $value
     * @param string $recommendationSecondary
     * @return array
     */
    protected function checkRequirement(string $condition, $recommendation, $value, $recommendationSecondary = '', $optional = false) {

        $status = 0;

        if(!empty($recommendationSecondary) && $this->compare($condition, $recommendationSecondary, $value)) {
            $status = 1;
        }

        if($this->compare($condition, $recommendation, $value)) {
            $status = 2;
        }

        if($optional === true) {
            $status = 3;

        }

        return array(
            'recommendation' =>  $recommendation,
            'value' => $value,
            //0 failed, 1 warning, 2 success, 3 optional
            'status' => $status
        );
    }

    /**
     * @noinspection ReturnTypeCanBeDeclaredInspection
     * @param $condition
     * @param $recommendation
     * @param $value
     * @return bool
     */
    protected function compare($condition, $recommendation, $value)
    {
        switch($condition) {
            case '=':
                if($value === $recommendation) {
                    return true;
                }
                break;

            case '<=':
                if((float)$value <= (float)$recommendation) {
                    return true;
                }
                break;

            case '>=':
                if((float)$value >= (float)$recommendation) {
                    return true;
                }
                break;

            case '<':
                if((float)$value < (float)$recommendation) {
                    return true;
                }
                break;

            case '>':
                if((float)$value > (float)$recommendation) {
                    return true;
                }
                break;

            case 'v+':
                if(version_compare($value, $recommendation, '>=')) {
                    return true;
                }
                break;
        }

        return false;
    }
}