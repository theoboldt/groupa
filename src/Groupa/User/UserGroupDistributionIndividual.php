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
use Groupa\GeneticAlgorithm\Individual;
use Traversable;

class UserGroupDistributionIndividual implements Individual, \IteratorAggregate, \Countable
{
    /**
     * Groups of containing user assignments
     *
     * @var GroupCollection
     */
    private $groups;

    /**
     * UserGroupDistributionIndividual constructor.
     *
     * @param GroupCollection $groupCollection Groups
     */
    public function __construct(GroupCollection $groupCollection)
    {
        $this->groups = $groupCollection;
    }

    /**
     * Provides fitness value for this individual
     *
     * @return Fitness
     */
    public function fitness(): Fitness
    {
        return new UserGroupFitness($this);
    }

    /**
     * Get group containing transmitted user
     *
     * @param User $user Defined user
     * @return Group
     */
    public function getGroupOfUser(User $user): Group
    {
        /** @var Group $group */
        foreach ($this->groups as $group) {
            if ($group->hasUser($user)) {
                return $group;
            }
        }
        throw new \RuntimeException('No group contains transmitted user');
    }

    /**
     * Retrieve an external iterator
     *
     * @link  http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return $this->groups->getIterator();
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
        return $this->groups->count();
    }
}