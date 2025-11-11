<?php
/**
 * File: TraceableEventDispatcherInterface.php
 * Description: Handles TraceableEventDispatcherInterface operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

namespace Symfony\Component\EventDispatcher\Debug;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @author Fabien Potencier <fabien@symfony.com>
 */
interface TraceableEventDispatcherInterface extends EventDispatcherInterface
{
    /**
     * Gets the called listeners.
     *
     * @return array An array of called listeners
     */
    public function getCalledListeners();

    /**
     * Gets the not called listeners.
     *
     * @return array An array of not called listeners
     */
    public function getNotCalledListeners();
}
