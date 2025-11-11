<?php
/**
 * File: Response.php
 * Description: Response class definition
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

namespace GraphAware\Bolt\Protocol\V1;

use GraphAware\Bolt\Exception\MessageFailureException;

class Response
{
    protected $completed = false;

    protected $records = [];

    protected $metadata = [];

    public function onSuccess($metadata)
    {
        $this->completed = true;
        $this->metadata[] = $metadata;
    }

    public function onRecord($metadata)
    {
        $this->records[] = $metadata;
    }

    public function getRecords()
    {
        return $this->records;
    }

    public function onFailure($metadata)
    {
        $this->completed = true;
        $e = new MessageFailureException($metadata->getElements()['message']);
        $e->setStatusCode($metadata->getElements()['code']);

        throw $e;
    }

    public function getMetadata()
    {
        return $this->metadata;
    }

    public function isCompleted()
    {
        return $this->completed;
    }
}