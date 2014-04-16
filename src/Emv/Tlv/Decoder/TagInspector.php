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

use Emv\Tlv\Codec\Tag;

/**
 * Provides tag byte inspection capabilities.
 *
 * @author Massimo Lombardo <unwiredbrain@gmail.com>
 */
class TagInspector
{
    /**
     * Extracts the tag class.
     *
     * @param int $byte The byte to work on.
     *
     * @return int Returns an INT representing the tag class.
     */
    public static function getClass($byte)
    {
        return $byte >> 6;
    }

    /**
     * Retrives the name of the raw tag.
     *
     * @param string $tag The raw tag to work on.
     *
     * @return string Returns a string containing the name of the raw tag. If not found, returns an empty string.
     */
    public static function getName($rawTag)
    {
        return Tag::getName($rawTag);
    }

    /**
     * Extracts the tag encoding.
     *
     * @param int $byte The byte to work on.
     *
     * @return int Returns an INT representing the tag encoding.
     */
    protected static function getEncoding($byte)
    {
        return $byte & 0x20;
    }

    /**
     * Extracts the tag number.
     *
     * @param int $byte The byte to work on.
     * @param bool $isMultiByte Whether the tag is multi-byte.
     *
     * @return int Returns an INT representing the tag number.
     */
    public static function getNumber($byte, $isMultiByte = false)
    {
        if (!!$isMultiByte) {
            return $byte & 0x7f;
        }

        return $byte & 0x1f;
    }

    /**
     * Detects whether the tag class is application.
     *
     * @param int $byte The byte to work on.
     *
     * @return bool Returns TRUE if the tag class is application, FALSE otherwise.
     */
    public static function isApplication($byte)
    {
        return Tag::CLASS_APPLICATION === self::getClass($byte);
    }

    /**
     * Detects whether the tag encoding is constructed.
     *
     * @param int $byte The byte to work on.
     *
     * @return bool Returns TRUE if the tag encoding is constructed, FALSE otherwise.
     */
    public static function isConstructed($byte)
    {
        return 0x20 === self::getEncoding($byte);
    }

    /**
     * Detects whether the tag class is context-specific.
     *
     * @param int $byte The byte to work on.
     *
     * @return bool Returns TRUE if the tag class is context-specific, FALSE otherwise.
     */
    public static function isContextSpecific($byte)
    {
        return Tag::CLASS_CONTEXTSPECIFIC === self::getClass($byte);
    }

    /**
     * Detects whether the byte represents the last byte of a multi-byte tag.
     *
     * @param int $byte The byte to work on.
     *
     * @return bool Returns TRUE if the byte represents the last byte of a multi-byte tag, FALSE otherwise.
     */
    public static function isLast($byte)
    {
        return 0x00 === ($byte >> 7);
    }

    /**
     * Detects whether the byte represents a long form tag byte.
     *
     * @param int $byte The byte to work on.
     *
     * @return bool Returns TRUE if the byte represents a long form tag, FALSE otherwise.
     */
    public static function isMultiByte($byte)
    {
        return 0x1f === ($byte & 0x1f);
    }

    /**
     * Detects whether the tag encoding is primitive.
     *
     * @param int $byte The byte to work on.
     *
     * @return bool Returns TRUE if the tag encoding is primitive, FALSE otherwise.
     */
    public static function isPrimitive($byte)
    {
        return 0x00 === self::getEncoding($byte);
    }

    /**
     * Detects whether the tag class is private.
     *
     * @param int $byte The byte to work on.
     *
     * @return bool Returns TRUE if the tag class is private, FALSE otherwise.
     */
    public static function isPrivate($byte)
    {
        return Tag::CLASS_PRIVATE === self::getClass($byte);
    }

    /**
     * Detects whether the tag class is universal.
     *
     * @param int $byte The byte to work on.
     *
     * @return bool Returns TRUE if the tag class is universal, FALSE otherwise.
     */
    public static function isUniversal($byte)
    {
        return Tag::CLASS_UNIVERSAL === self::getClass($byte);
    }

    /**
     * Detects whether the byte represents a valid tag byte.
     *
     * @param int $byte The byte to work on.
     * @param int $byteNumber Which tag byte is being analyzed: the 1st, the 2nd or the 3rd.
     *
     * @return bool Returns TRUE if the byte represents a valid tag, FALSE otherwise.
     */
    public static function isValid($byte, $byteNumber = 1)
    {
        if ($byteNumber <= 2 && $byte === 0x00) {
            return false;
        }

        if ($byteNumber === 2 && ($byte === 0x1e || $byte === 0x80)) {
            return false;
        }

        return true;
    }
}
