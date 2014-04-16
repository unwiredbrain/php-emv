<?php

/*
 * This file is part of the EMV Utilities package.
 *
 * (c) Massimo Lombardo <unwiredbrain@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Emv\Tlv\Codec;

/**
 * Provides common tag byte properties.
 *
 * @author Massimo Lombardo <unwiredbrain@gmail.com>
 */
class Tag
{
    /**
     * Application class bits.
     */
    const CLASS_APPLICATION = 0x01;

    /**
     * Context-Specific class bits.
     */
    const CLASS_CONTEXTSPECIFIC = 0x02;

    /**
     * Private class bits.
     */
    const CLASS_PRIVATE = 0x03;

    /**
     * Universal class bits.
     */
    const CLASS_UNIVERSAL = 0x00;

    /**
     * All the known tags names.
     *
     * @var string[]
     */
    private static $names = array(
        '42' => 'Issuer Identification Number (IIN)',
        '4F' => 'Application Identifier (AID) – card',
        '50' => 'Application Label',
        '57' => 'Track 2 Equivalent Data',
        '5A' => 'Application Primary Account Number (PAN)',
        '61' => 'Application Template',
        '6F' => 'File Control Information (FCI) Template',
        '70' => 'EMV Proprietary Template',
        '71' => 'Issuer Script Template 1',
        '72' => 'Issuer Script Template 2',
        '73' => 'Directory Discretionary Template',
        '77' => 'Response Message Template Format 2',
        '80' => 'Response Message Template Format 1',
        '81' => 'Amount, Authorised (Binary)',
        '82' => 'Application Interchange Profile',
        '83' => 'Command Template',
        '84' => 'Dedicated File (DF) Name',
        '86' => 'Issuer Script Command',
        '87' => 'Application Priority Indicator',
        '88' => 'Short File Identifier (SFI)',
        '89' => 'Authorisation Code',
        '8A' => 'Authorisation Response Code',
        '8C' => 'Card Risk Management Data Object List 1 (CDOL1)',
        '8D' => 'Card Risk Management Data Object List 2 (CDOL2)',
        '8E' => 'Cardholder Verification Method (CVM) List',
        '8F' => 'Certification Authority Public Key Index',
        '90' => 'Issuer Public Key Certificate',
        '91' => 'Issuer Authentication Data',
        '92' => 'Issuer Public Key Remainder',
        '93' => 'Signed Static Application Data',
        '94' => 'Application File Locator (AFL)',
        '95' => 'Terminal Verification Results',
        '97' => 'Transaction Certificate Data Object List (TDOL)',
        '98' => 'Transaction Certificate (TC) Hash Value',
        '99' => 'Transaction Personal Identification Number (PIN) Data',
        '9A' => 'Transaction Date',
        '9B' => 'Transaction Status Information',
        '9C' => 'Transaction Type',
        '9D' => 'Directory Definition File (DDF) Name',
        'A5' => 'File Control Information (FCI) Proprietary Template',
        'E0' => 'Receive Data Elements',
        'E1' => 'Issue Data Elements (Secure)',
        'E2' => 'Decision',
        'E3' => 'Issue Data Elements (Non-Secure)',
        'C2' => 'Encrypted Data',
        '5F20' => 'Cardholder Name',
        '5F24' => 'Application Expiration Date',
        '5F25' => 'Application Effective Date',
        '5F28' => 'Issuer Country Code',
        '5F2A' => 'Transaction Currency Code',
        '5F2D' => 'Language Preference',
        '5F30' => 'Service Code',
        '5F34' => 'Application Primary Account Number (PAN) Sequence Number',
        '5F36' => 'Transaction Currency Exponent',
        '5F50' => 'Issuer URL',
        '5F53' => 'International Bank Account Number (IBAN)',
        '5F54' => 'Bank Identifier Code (BIC)',
        '5F55' => 'Issuer Country Code (alpha2 format)',
        '5F56' => 'Issuer Country Code (alpha3format)',
        '5F57' => 'Account Type',
        '9F01' => 'Acquirer Identifier',
        '9F02' => 'Amount, Authorised (Numeric)',
        '9F03' => 'Amount, Other (Numeric)',
        '9F04' => 'Amount, Other (Binary)',
        '9F05' => 'Application Discretionary Data',
        '9F06' => 'Application Identifier (AID) – terminal',
        '9F07' => 'Application Usage Control',
        '9F08' => 'Application Version Number',
        '9F09' => 'Application Version Number',
        '9F0B' => 'Cardholder Name Extended',
        '9F0D' => 'Issuer Action Code – Default',
        '9F0E' => 'Issuer Action Code – Denial',
        '9F0F' => 'Issuer Action Code – Online',
        '9F10' => 'Issuer Application Data',
        '9F11' => 'Issuer Code Table Index',
        '9F12' => 'Application Preferred Name',
        '9F13' => 'Last Online Application Transaction Counter (ATC) Register',
        '9F14' => 'Lower Consecutive Offline Limit',
        '9F15' => 'Merchant Category Code',
        '9F16' => 'Merchant Identifier',
        '9F17' => 'Personal Identification Number (PIN) Try Counter',
        '9F18' => 'Issuer Script Identifier',
        '9F1A' => 'Terminal Country Code',
        '9F1B' => 'Terminal Floor Limit',
        '9F1C' => 'Terminal Identification',
        '9F1D' => 'Terminal Risk Management Data',
        '9F1E' => 'Interface Device (IFD) Serial Number',
        '9F1F' => 'Track 1 Discretionary Data',
        '9F20' => 'Track 2 Discretionary Data',
        '9F21' => 'Transaction Time',
        '9F22' => 'Certification Authority Public Key Index',
        '9F23' => 'Upper Consecutive Offline Limit',
        '9F26' => 'Application Cryptogram',
        '9F27' => 'Cryptogram Information Data',
        '9F2D' => 'Integrated Circuit Card (ICC) PIN Encipherment Public Key Certificate',
        '9F2E' => 'Integrated Circuit Card (ICC) PIN Encipherment Public Key Exponent',
        '9F2F' => 'Integrated Circuit Card (ICC) PIN Encipherment Public Key Remainder',
        '9F32' => 'Issuer Public Key Exponent',
        '9F33' => 'Terminal Capabilities',
        '9F34' => 'Cardholder Verification Method (CVM) Results',
        '9F35' => 'Terminal Type',
        '9F36' => 'Application Transaction Counter (ATC)',
        '9F37' => 'Unpredictable Number',
        '9F38' => 'Processing Options Data Object List (PDOL)',
        '9F39' => 'Point-of-Service (POS) Entry Mode',
        '9F3A' => 'Amount, Reference Currency',
        '9F3B' => 'Application Reference Currency',
        '9F3C' => 'Transaction Reference Currency Code',
        '9F3D' => 'TransactionReference Currency Exponent',
        '9F40' => 'Additional Terminal Capabilities',
        '9F41' => 'Transaction SequenceCounter',
        '9F42' => 'Application Currency Code',
        '9F43' => 'Application Reference Currency Exponent',
        '9F44' => 'Application Currency Exponent',
        '9F45' => 'Data Authentication Code',
        '9F46' => 'Integrated Circuit Card (ICC) Public Key Certificate',
        '9F47' => 'Integrated Circuit Card (ICC) Public Key Exponent',
        '9F48' => 'Integrated Circuit Card (ICC) Public Key Remainder',
        '9F49' => 'Dynamic Data Authentication Data Object List(DDOL)',
        '9F4A' => 'Static Data Authentication Tag List',
        '9F4B' => 'Signed Dynamic Application Data',
        '9F4C' => 'ICC Dynamic Number',
        '9F4D' => 'Log Entry',
        '9F4E' => 'Merchant Name and Location',
        '9F4F' => 'Log Format',
        'BF0C' => 'File Control Information (FCI) Issuer Discretionary Data',
        'DF01' => 'Digits',
        'DF02' => 'Status Bytes',
        'DF11' => 'Acquirer ID',
        'DF12' => 'Answer To Reset (ATR)',
        'DF13' => 'ISO 7811 Track 1 card data',
        'DF14' => 'ISO 7811 Track 2 card data',
        'DF15' => 'ISO 7811 Track 3 card data',
        'DF16' => 'Enciphered PIN Block',
        'DF17' => 'Key Serial Number',
        'DF18' => 'Risk Management'
    );

    /**
     * Retrives the name of the tag.
     *
     * @param string $tag The tag to work on.
     *
     * @return string Returns a string containing the name of the tag. If not found, returns an empty string.
     */
    public static function getName($tag)
    {
        if (isset(self::$names[$tag])) {
            return self::$names[$tag];
        }

        return '';
    }
}
