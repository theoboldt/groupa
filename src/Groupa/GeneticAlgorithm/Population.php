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


class Population implements \Countable
{

    /**
     * Stores individuals
     *
     * @var array|Individual[]
     */
    private $individuals = [];

    /**
     * Initialize a population using transmitted individual creator
     *
     * @param int               $size    Target size of population
     * @param IndividualCreator $creator Creator for individuals
     * @return Population
     */
    public static function initialize(int $size, IndividualCreator $creator)
    {
        $population = new self;
        for ($i = 0; $i < $size; ++$i) {
            $population->add($creator->createByRandom());
        }
        return $population;
    }

    /**
     * Add individual to pool
     *
     * @param Individual $individual
     * @return Population
     */
    public function add(Individual $individual)
    {
        $this->individuals[] = $individual;
        return $this;
    }

    /**
     * Sort by fitness
     *
     * @return void
     */
    public function sort(): void
    {
        uasort(
            $this->individuals, function ($a, $b) {
            $a = $a->fitness()->asInteger();
            $b = $b->fitness()->asInteger();

            if ($a == $b) {
                return 0;
            }
            return ($a < $b) ? -1 : 1;
        }
        );
    }

    /**
     * Get fittest @see Individual of population
     *
     * @param int|null $maxEntries Max entries or null for unlimited
     * @return array|Individual[]
     */
    public function getFittest(int $maxEntries = null)
    {
        $this->sort();
        if ($maxEntries === null || $this->count() < $maxEntries) {
            return $this->individuals;
        }
        return array_slice($this->individuals, -$maxEntries, $maxEntries, true);
    }

    /**
     * Count elements of an object
     *
     * @link  http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->individuals);
    }
}