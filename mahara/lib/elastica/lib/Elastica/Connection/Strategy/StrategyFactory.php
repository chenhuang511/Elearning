<?php

namespace Elastica\Connection\Strategy;

use Elastica\Exception\InvalidException;

/**
 * Description of StrategyFactory
 *
 * @author chabior
 */
class StrategyFactory
{
    /**
     * @throws \Elastica\Exception\InvalidException
     *
     * @param  mixed|Closure|String|StrategyInterface          $strategyName
     * @return \Elastica\Connection\Strategy\StrategyInterface
     */
    public static function create($strategyName)
    {
        $strategy = null;
        if ($strategyName instanceof StrategyInterface) {
            $strategy = $strategyName;
        } elseif (CallbackStrategy::isValid($strategyName)) {
            $strategy = new CallbackStrategy($strategyName);
        } elseif (is_string($strategyName) && class_exists($strategyName)) {
            $strategy = new $strategyName();
        } elseif (is_string($strategyName)) {
            $pathToStrategy = '\\Elastica\\Connection\\Strategy\\'.$strategyName;
            if (class_exists($pathToStrategy)) {
                $strategy = new $pathToStrategy();
            }
        }

        if (!$strategy instanceof StrategyInterface) {
            throw new InvalidException('Can\'t load strategy class');
        }

        return $strategy;
    }
}
