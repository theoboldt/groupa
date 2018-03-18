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


class Evoluter
{

    /**
     * Rate of fittest @see Individual to be put into next generation
     *
     */
    private $survivorRate = 0.2;

    /**
     * Rate of survived @see Individual to be kept into new generation without modification
     *
     * @var float
     */
    private $uniformSurvivorRate = 0.2;

    /**
     * Rate of mutation after performing crossover
     *
     * @var float
     */
    private $mutationRate = 0.20;

    /**
     * When selecting for crossover how large each pool should be
     *
     * @var int
     */
    private $poolSize = 10;

    /**
     * Creator for @see Individual
     *
     * @var IndividualCreator
     */
    private $creator;

    /**
     * Get current population
     *
     * @var Population|null
     */
    private $population;

    /**
     * Evoluter constructor.
     *
     * @param IndividualCreator $creator
     */
    public function __construct(IndividualCreator $creator)
    {
        $this->creator = $creator;
    }


    /**
     * Get next population generation
     */
    public function getNextGeneration()
    {
        if ($this->population === null) {
            $this->population = Population::initialize($this->poolSize, $this->creator);
        } else {
            $this->population = $this->evolve();
        }

        return $this->population;
    }

    /**
     * Evolve
     *
     * @return Population
     */
    private function evolve()
    {
        $population = new Population();

        $fittest = $this->population->getFittest((int)$this->poolSize * $this->survivorRate);
        shuffle($fittest);
        $uniformSurvivors = count($fittest) * $this->uniformSurvivorRate;

        for ($i = 0; $i < $uniformSurvivors; ++$i) {
            if (count($fittest)) {
                $population->add(array_pop($fittest));
            }
        }

        while (count($fittest)) {
            $a = array_pop($fittest);
            if (count($fittest)) {
                $b = array_pop($fittest);
                $population->add($this->creator->createByCrossover($a, $b));
            } else {
                $population->add($a);
            }
        }

        return $population;
    }
}