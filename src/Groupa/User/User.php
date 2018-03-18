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


class User
{
    /**
     * User Age in years
     *
     * @var float
     */
    private $age;

    /**
     * User constructor.
     *
     * @param float $age
     */
    public function __construct(float $age)
    {
        $this->age = $age;
    }

    /**
     * Get user age in years
     *
     * @return float
     */
    public function getAge()
    {
        return $this->age;
    }
}