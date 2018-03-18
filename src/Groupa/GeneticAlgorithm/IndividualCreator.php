<?php
/**
 * This file is part of the Juvem package.
 *
 * (c) Erik Theoboldt <erik@theoboldt.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Groupa\GeneticAlgorithm;


interface IndividualCreator
{

    /**
     * Create individual completely random for first generation
     *
     * @return Individual
     */
    public function createByRandom(): Individual;

    /**
     * Create new individual by crossover of 2 parents
     *
     * @param  Individual $parentA Parent crossover
     * @param  Individual $parentB Parent crossover
     * @return Individual Combined individual
     */
    public function createByCrossover(Individual $parentA, Individual $parentB): Individual;

}