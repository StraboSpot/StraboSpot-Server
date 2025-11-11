<?php
/**
 * File: IoInterface.php
 * Description: Handles IoInterface operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

namespace GraphAware\Bolt\IO;

interface IoInterface
{
    public function write($data);

    public function read($n);

    public function select($sec, $usec);

    public function connect();

    public function reconnect();

    public function isConnected();

    public function close();
}