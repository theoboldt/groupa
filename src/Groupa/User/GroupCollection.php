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

use Traversable;

class GroupCollection implements \IteratorAggregate, \Countable
{
    /**
     * Groups of containing user assignments
     *
     * @var array|Group[]
     */
    private $groups = [];

    /**
     * Add group to collection
     *
     * @param Group $group
     */
    public function addGroup(Group $group)
    {
        if ($this->hasGroup($group->getNumber())) {
            throw new \InvalidArgumentException('Collection already contains transmitted group');
        }
        $this->groups[$group->getNumber()] = $group;
    }

    /**
     * Determine if a group with transmitted number is available
     *
     * @param int $number
     * @return bool
     */
    public function hasGroup(int $number): bool
    {
        return isset($this->groups[$number]);
    }

    /**
     * Get group with transmitted number
     *
     * @param int $number
     * @return Group
     */
    public function getGroup(int $number): Group
    {
        if (!$this->hasGroup($number)) {
            throw new \InvalidArgumentException('Group having transmitted number does not exist');
        }
        return $this->groups[$number];
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
        return new \ArrayIterator($this->groups);
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
        return count($this->groups);
    }

}