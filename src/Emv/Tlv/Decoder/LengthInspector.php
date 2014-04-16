<?php

/*
 * This file is part of the EMV Utilities package.
 *
 * (c) Massimo Lombardo <unwiredbrain@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Emv\Tlv\Decoder;

/**
 * Provides length byte inspection capabilities.
 *
 * @author Massimo Lombardo <unwiredbrain@gmail.com>
 */
class LengthInspector
{
    /**
     * Extracts the length of a (multi-)length byte.
     *
     * @param int $byte The byte to work on.
     *
     * @return int Returns an INT representing length of a (multi-)length byte.
     */
    public static function getLength($byte)
    {
        return $byte & 0x7f;
    }

    /**
     * Detects whether the byte represents a long form length byte.
     *
     * @param int $byte The byte to work on.
     *
     * @return bool Returns TRUE if the byte represents a long length byte, FALSE otherwise.
     */
    public static function isMultiByte($byte)
    {
        return 0x01 === ($byte >> 7);
    }

    /**
     * Detects whether the byte represents a valid length byte.
     *
     * @param int $byte The byte to work on.
     *
     * @return bool Returns TRUE if the byte represents a valid length byte, FALSE otherwise.
     */
    public static function isValid($byte)
    {
        if ($byte !== 0x80 && $byte >= 0x00 && $byte <= 0x84) {
            return true;
        }

        return false;
    }
}
