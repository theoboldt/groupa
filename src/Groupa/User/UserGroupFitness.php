<?php
/**
 * This file is part of the Juvem package.
 *
 * (c) Erik Theoboldt <erik@theoboldt.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Groupa\User;


use Groupa\GeneticAlgorithm\Fitness;

class UserGroupFitness implements Fitness
{

    /**
     * Stores age deviation average
     *
     * @var float
     */
    private $ageDeviationAverage;

    /**
     * Fitness constructor.
     *
     * @param UserGroupDistributionIndividual $individual Individual used to calculate fitness
     */
    public function __construct(UserGroupDistributionIndividual $individual)
    {
        $values = [];
        /** @var Group $group */
        foreach ($individual as $group) {
            $values[] = $group->getAgeStandardDeviation();
        }

        if (!count($values)) {
            return null;
        }

        $this->ageDeviationAverage = array_sum($values) / count($values);
    }

    /**
     * {@inheritdoc}
     */
    public function asInteger(): int
    {
        return round((1 / $this->ageDeviationAverage) * 1000);
    }
}