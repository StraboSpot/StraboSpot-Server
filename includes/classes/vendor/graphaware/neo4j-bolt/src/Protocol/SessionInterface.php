<?php
/**
 * File: SessionInterface.php
 * Description: Handles SessionInterface operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

namespace GraphAware\Bolt\Protocol;

interface SessionInterface
{
    public static function getProtocolVersion();

    /**
     * @param $statement
     * @param array $parameters
     * @return \GraphAware\Bolt\Result\Result
     */
    public function run($statement, array $parameters = array());

    public function runPipeline(Pipeline $pipeline);

    /**
     * @return \GraphAware\Bolt\Protocol\Pipeline
     */
    public function createPipeline();

    public function close();

    /**
     * @return \GraphAware\Bolt\Protocol\V1\Transaction
     */
    public function transaction();
}