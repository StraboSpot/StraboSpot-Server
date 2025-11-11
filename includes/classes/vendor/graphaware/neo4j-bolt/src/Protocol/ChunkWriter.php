<?php
/**
 * File: ChunkWriter.php
 * Description: ChunkWriter class definition
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

namespace GraphAware\Bolt\Protocol;

use GraphAware\Bolt\IO\AbstractIO;
use GraphAware\Bolt\PackStream\Packer;

class ChunkWriter
{
    const MAX_CHUNK_SIZE = 8192;

    /**
     * @var \GraphAware\Bolt\IO\AbstractIO
     */
    protected $io;

    /**
     * @var \GraphAware\Bolt\PackStream\Packer
     */
    protected $packer;

    /**
     * @param \GraphAware\Bolt\IO\AbstractIO $io
     */
    public function __construct(AbstractIO $io, Packer $packer)
    {
        $this->io = $io;
        $this->packer = $packer;
    }

    /**
     * @param \GraphAware\Bolt\Protocol\Message\AbstractMessage[] $messages
     */
    public function writeMessages(array $messages)
    {
        $raw = '';
        foreach ($messages as $msg) {
            $chunkData = $msg->getSerialization();
            $chunks = $this->splitChunk($chunkData);
            foreach ($chunks as $chunk) {
                $raw .= $this->packer->getSizeMarker($chunk);
                $raw .= $chunk;
            }
            $raw .= $this->packer->getEndSignature();
        }
        $this->io->write($raw);
    }

    public function splitChunk($data)
    {
        $chunks = str_split($data, self::MAX_CHUNK_SIZE);

        return $chunks;
    }
}