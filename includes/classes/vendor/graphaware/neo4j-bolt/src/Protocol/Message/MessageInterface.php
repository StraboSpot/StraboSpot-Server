<?php
/**
 * File: MessageInterface.php
 * Description: Handles MessageInterface operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

namespace GraphAware\Bolt\Protocol\Message;

interface MessageInterface
{
    public function getSignature();

    public function getMessageType();

    public function getFields();

    public function isSuccess();

    public function isFailure();

}