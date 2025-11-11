<?php
/**
 * File: Configuration.php
 * Description: Configuration class definition
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

namespace GraphAware\Bolt;

use GraphAware\Common\Driver\ConfigInterface;

class Configuration implements ConfigInterface
{
    protected $credentials;

    public function __construct($username, $password)
    {
        $this->credentials = array($username, $password);
    }

    public static function withCredentials($username, $password)
    {
        return new self($username, $password);
    }

    /**
     * @return array
     */
    public function getCredentials()
    {
        return $this->credentials;
    }
}