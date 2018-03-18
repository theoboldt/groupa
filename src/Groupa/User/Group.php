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


class Group implements \Countable
{
    /**
     * Group number
     *
     * @var int
     */
    private $number;

    /**
     * Stores assigned users
     *
     * @var array|User[]
     */
    private $users = [];

    /**
     * Group constructor.
     *
     * @param int $number Group number
     */
    public function __construct(int $number)
    {
        $this->number = $number;
    }

    /**
     * Add user to group
     *
     * @param User $user User
     */
    public function addUser(User $user)
    {
        $id = spl_object_hash($user);
        if (isset($this->users[$id])) {
            throw new \InvalidArgumentException('Group already contains transmitted user');
        }
        $this->users[$id] = $user;
    }

    /**
     * Get average age of all users
     *
     * @return float Average age
     */
    public function getAgeAverage()
    {
        $ages = [];

        if (!count($this)) {
            throw new \InvalidArgumentException('Can not calculate average age of empty group');
        }

        foreach ($this->users as $user) {
            $ages[] = $user->getAge();
        }

        return array_sum($ages) / count($ages);
    }

    /**
     * Get age variance
     *
     * @return float
     */
    public function getAgeVariance()
    {
        if (!count($this)) {
            return 0;
        }
        $average = $this->getAgeAverage();

        $quadDiff = [];

        foreach ($this->users as $user) {
            $quadDiff[] = ($user->getAge() - $average) ** 2;
        }

        return array_sum($quadDiff) / count($this);
    }

    /**
     * Get age standard deviation
     *
     * @return float
     */
    public function getAgeStandardDeviation()
    {
        return sqrt($this->getAgeVariance());
    }

    /**
     * Get transmitted user of group
     *
     * @param User $user
     * @return User
     */
    public function getUser(User $user): User
    {
        if (!$this->hasUser($user)) {
            throw new \InvalidArgumentException('This group does not contain transmitted user');
        }
        $id = spl_object_hash($user);
        return $this->users[$id];
    }

    /**
     * Determine if this group contains transmitted user
     *
     * @param User $user
     * @return bool
     */
    public function hasUser(User $user): bool
    {
        $id = spl_object_hash($user);
        return isset($this->users[$id]);
    }

    /**
     * Get number
     *
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
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
        return count($this->users);
    }
}