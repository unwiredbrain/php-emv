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

use Emv\Tlv\Decoder\TagInspector as Tag;
use Emv\Tlv\Decoder\LengthInspector as Length;
use Emv\Tlv\Exception\MalformedDataBlockException;

/**
 * Encoder is a tool to serialize structured trees into EMV TLV-encoded data blocks.
 *
 * @author Massimo Lombardo <unwiredbrain@gmail.com>
 */
class Encoder
{
    /**
     * Encodes an EMV TLV data structure.
     *
     * @param \stdClass|\stdClass[] $data The EMV TLV data structure.
     *
     * @return string Returns a string representing the encoded EMV TLV data
     *                structure.
     *
     * @throws MalformedDataBlockException If the EMV TLV data structure
     *         contains other data than arrays and \stdClass instances.
     */
    public function serialize($data)
    {
        if (!($data instanceof \stdClass) && !is_array($data)) {
            throw new MalformedDataBlockException('malformed data structure');
        }

        if (is_array($data)) {
            return $this->encodeArray($data);
        }

        if ($data instanceof \stdClass) {
            return $this->encodeObject($data);
        }

        return '';
    }

    /**
     * Encodes an array of stdClass instances.
     *
     * @param \stdClass[] $data A list of EMV TLV data structures.
     *
     * @return string Returns a string representing the encoded EMV TLV data
     *                structure.
     */
    protected function encodeArray(array $data)
    {
        $output = array();

        foreach ($data as $tag => $object) {
            if ($object instanceof \stdClass) {
                $object->tag = $tag;
                $output[] = $this->encodeObject($object);
            }
        }

        return implode('', $output);
    }

    /**
     * Encodes a stdClass instance.
     *
     * @param \stdClass $data The EMV TLV data structure.
     *
     * @return string Returns a string representing the encoded EMV TLV data
     *                structure.
     */
    protected function encodeObject(\stdClass $data)
    {
        if (!isset($data->tag, $data->value) || !in_array(strlen($data->tag), array(2, 4, 6))) {
            return '';
        }

        $value = (string) $data->value;

        if (Tag::isConstructed(hexdec(substr($data->tag, 0, 2)))) {
            $value = $this->encode($data->value);
        }

        $length = strlen($value) / 2;

        /*
         * ISO/IEC 7816-4:2013, sect. 5.2.2.2: "ISO/IEC 7816 supports length
         * fields [...] up to five bytes."
         *
         * This means that the value field can be made of 4294967295 (FFFFFFFF)
         * bytes at most.
         *
         * If the length excesses this value, bail out.
         */
        if ($length > 4294967295) {
            return '';
        }

        $bytes = ($length < 128) ? 1 : (($length < 256) ? 2 : (($length < 65536) ? 3 : (($length < 16777216) ? 4 : 5)));

        if ($bytes === 1) {
            $length = sprintf('%02X', $length);
        } else {
            $length = sprintf('%032b', $length);

            $length = str_split($length, 8);

            $length = array_map(function ($bits) {
                return sprintf('%02X', bindec($bits));
            }, $length);

            $length = array_filter($length, function ($bits) {
                return '00' !== $bits;
            });

            $length = implode('', $length);

            $length = strval(79 + $bytes) . $length;
        }

        return sprintf('%s%s%s', $data->tag, $length, $value);
    }
}
