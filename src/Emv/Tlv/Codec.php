<?php

/*
 * This file is part of the EMV Utilities package.
 *
 * (c) Massimo Lombardo <unwiredbrain@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Emv\Tlv;

use Emv\Tlv\Decoder;
use Emv\Tlv\Encoder;

/**
 * Decoder is a tool to unserialize EMV TLV-encoded payloads into structured
 * trees and back.
 *
 * @author Massimo Lombardo <unwiredbrain@gmail.com>
 */
class Codec
{
    /**
     * @var Decoder
     */
    private $decoder;

    /**
     * @var Encoder
     */
    private $encoder;

    /**
     * Serializes a structured tree into an EMV TLV payload.
     *
     * @param \stdClass|\stdClass[] $tree The structured tree.
     *
     * @return string Returns a string representing the encoded EMV TLV payload.
     */
    public function serialize($tree)
    {
        // Lazy-load the encoder.
        if (null === $this->encoder) {
            $this->encoder = new Encoder();
        }

        return $this->encoder->serialize($tree);
    }

    /**
     * Unserializes an encoded EMV TLV payload into a structured tree.
     *
     * @param string $payload The encoded EMV TLV payload.
     *
     * @return \stdClass[] Returns a structured tree representing the encoded
     *                     EMV TLV payload. May contain nested structured trees.
     */
    public function unserialize($payload)
    {
        // Lazy-load the decoder.
        if (null === $this->decoder) {
            $this->decoder = new Decoder();
        }

        return $this->decoder->unserialize($payload);
    }
}
