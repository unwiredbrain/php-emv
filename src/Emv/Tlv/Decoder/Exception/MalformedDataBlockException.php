<?php

/*
 * This file is part of the EMV Utilities package.
 *
 * (c) Massimo Lombardo <unwiredbrain@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Emv\Tlv\Decoder\Exception;

/**
 * Thrown when a EMV TLV data block is malformed.
 *
 * @author Massimo Lombardo <unwiredbrain@gmail.com>
 */
class MalformedDataBlockException extends \RuntimeException implements ExceptionInterface
{
}
