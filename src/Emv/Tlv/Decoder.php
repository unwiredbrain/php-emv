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
use Emv\Tlv\Decoder\Exception\MalformedDataBlockException;

/**
 * Decoder is a tool to unserialize EMV TLV-encoded data blocks into structured trees.
 *
 * @author Massimo Lombardo <unwiredbrain@gmail.com>
 */
class Decoder
{
    /**
     * Decodes an EMV TLV-encoded data block.
     *
     * @param string $bytes The EMV TLV data block.
     *
     * @return \stdClass[] Returns a list of \stdClass instances containing the
     *         parsed EMV TLV-encoded data. May contain nested lists of objects.
     *
     * @throws MalformedDataBlockException If the data block contains forbidden
     *         characters (not in the [0-9a-fA-F] range) or if the data block
     *         length is not even.
     */
    public function unserialize($bytes)
    {
        if (!ctype_xdigit($bytes) || strlen($bytes) % 2 === 1) {
            throw new MalformedDataBlockException('malformed EMV TLV-encoded data block');
        }

        $bytes = str_split($bytes, 2);

        $bytes = array_map('hexdec', $bytes);

        return $this->decodeTlv($bytes);
    }

    /**
     * Decodes a list of bytes representing some EMV TLV-encoded data.
     *
     * @param int[] $bytes The list of bytes.
     *
     * @return \stdClass[] Returns a list of \stdClass instances containing the
     *         parsed EMV TLV-encoded data. May contain nested lists of objects.
     */
    protected function decodeTlv($bytes)
    {
        $tree = array();

        $extent = count($bytes);
        $cursor = 0;

        while ($cursor < $extent) {

            // Tag
            // -----------------------------------------------------------------

            if (!isset($bytes[$cursor])) {
                break;
            }

            $tag = array($bytes[$cursor]);

            // Is the tag valid? No? Move the cursor forward and skip any
            // remaining computation.
            if (!Tag::isValid($tag[0])) {
                /**
                 * Trigger an error? Throw an exception? Log something? No! To
                 * be really useful such a message should include the original
                 * EMV-TLV data block, which we cannot disclose inside the log
                 * files because of PCI DSS requirements.
                 */
                $cursor++;

                continue;
            }

            $tagIsConstructed = Tag::isConstructed($tag[0]);

            if (Tag::isMultiByte($tag[0])) {

                // Two-byte tag
                // -------------------------------------------------------------

                $cursor++;

                if (!isset($bytes[$cursor])) {
                    break;
                }

                $tag[] = $bytes[$cursor];

                if (!Tag::isLast($bytes[$cursor])) {

                // Three-byte tag
                // -------------------------------------------------------------

                    $cursor++;

                    if (!isset($bytes[$cursor])) {
                        break;
                    }

                    $tag[] = $bytes[$cursor];
                }
            }

            $tag = array_map(function ($byte) {
                return sprintf('%02X', $byte);
            }, $tag);

            $tag = implode('', $tag);

            $cursor++;

            // Length
            // -----------------------------------------------------------------

            if (!isset($bytes[$cursor])) {
                break;
            }

            $length = $bytes[$cursor];

            if (!Length::isValid($length)) {
                /**
                 * Trigger an error? Throw an exception? Log something? No! To
                 * be really useful such a message should include the original
                 * EMV TLV data block, which we cannot disclose inside the log
                 * files because of PCI DSS requirements.
                 */
                break;
            }

            $length = Length::getLength($length);

            if (Length::isMultiByte($bytes[$cursor])) {
                $length_cursor = 0;
                $length_extent = $length;

                $length = array();

                while ($length_cursor < $length_extent) {
                    $cursor++;

                    if (!isset($bytes[$cursor])) {
                        break;
                    }

                    $length[] = $bytes[$cursor] << (($length_extent - $length_cursor - 1) * 8);
                    $length_cursor++;
                }

                if (!isset($bytes[$cursor])) {
                    break;
                }

                $length_output = 0;

                foreach ($length as $length_part) {
                    $length_output = $length_output | $length_part;
                }

                $length = $length_output;

            }

            $cursor++;

            // Value
            // -----------------------------------------------------------------

            if (!isset($bytes[$cursor])) {
                break;
            }

            $value = array_slice($bytes, $cursor, $length);

            if ($tagIsConstructed) {
                $value = $this->decodeTlv($value);
            } else {
                $value = array_map(function ($byte) {
                    return sprintf('%02x', $byte);
                }, $value);

                $value = implode('', $value);
            }

            $cursor += $length;

            // All together now!
            // -----------------------------------------------------------------

            $leaf = new \stdClass();
            $leaf->name = Tag::getName($tag);
            $leaf->length = $length;
            $leaf->value = $value;

            // Add it to the output, now!
            // -----------------------------------------------------------------

            $tree[$tag] = $leaf;
        }

        return $tree;
    }
}
