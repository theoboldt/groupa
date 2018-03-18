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


use Groupa\GeneticAlgorithm\Individual;
use Groupa\GeneticAlgorithm\IndividualCreator;

class UserGroupDistributionCreator implements IndividualCreator
{

    /**
     * Raw list of users without grouping used to createByRandom this @see Individual
     *
     * @var array|User[]
     */
    private $users = [];

    /**
     * Minimum count of groups
     *
     * @var int
     */
    private $groupCountMin;

    /**
     * Maximum count of groups
     *
     * @var int
     */
    private $groupCountMax;

    /**
     * RandomUserGroupDistributionCreator constructor.
     *
     * @param array|User[] $users
     * @param int          $groupCountMin
     * @param int          $groupCountMax
     */
    public function __construct(array $users, int $groupCountMin, int $groupCountMax)
    {
        $this->users         = $users;
        $this->groupCountMin = $groupCountMin;
        $this->groupCountMax = $groupCountMax;
    }

    /**
     * Create random amount of groups in between count minimum and maximum
     *
     * @return GroupCollection
     */
    private function createRandomGroups()
    {
        $groupsCount = rand($this->groupCountMin, $this->groupCountMax);
        return self::createGroups($groupsCount);
    }

    /**
     * Create defined amount of groups
     *
     * @param int $count
     * @return GroupCollection
     */
    private static function createGroups(int $count)
    {
        $collection = new GroupCollection();
        for ($i = 0; $i <= $count; ++$i) {
            $collection->addGroup(new Group($i));
        }
        return $collection;
    }

    /**
     * Create individual by random
     *
     * @return UserGroupDistributionIndividual
     */
    public function createByRandom(): Individual
    {
        $groups = $this->createRandomGroups();
        $users  = $this->users;

        shuffle($users);

        while (count($users)) {
            /** @var \Groupa\User\Group $group */
            foreach ($groups as $group) {
                $user = array_pop($users);
                if ($user) {
                    $group->addUser($user);
                }
            }
        }

        return new UserGroupDistributionIndividual($groups);
    }

    /**
     * Create individual by crossover
     *
     * @param UserGroupDistributionIndividual $parentA Parent crossover
     * @param UserGroupDistributionIndividual $parentB Parent crossover
     * @return UserGroupDistributionIndividual Combined individual
     */
    public function createByCrossover(Individual $parentA, Individual $parentB): Individual
    {
        if (!$parentA instanceof UserGroupDistributionIndividual
            || !$parentB instanceof UserGroupDistributionIndividual
        ) {
            throw new \InvalidArgumentException('This creator requires UserGroupDistributionIndividual for crossover');
        }

        $groups = self::createGroups(count($parentA));

        $take = false;
        foreach ($this->users as $user) {
            if ($take) {
                $groupOld = $parentA->getGroupOfUser($user);
            } else {
                $groupOld = $parentB->getGroupOfUser($user);
            }

            $targetNumber = $groupOld->getNumber();
            if (!$groups->hasGroup($targetNumber)) {
                $targetNumber = 0;
            }

            $groupNew = $groups->getGroup($targetNumber);
            $groupNew->addUser($user);

            $take = !(bool)$take;
        }
        return new UserGroupDistributionIndividual($groups);
    }
}