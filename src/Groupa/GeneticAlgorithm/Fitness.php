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


interface Fitness
{
    /**
     * Provide fitness value as integer, the higher the better
     *
     * @return int
     */
    public function asInteger(): int;

}