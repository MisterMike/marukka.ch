<?php


namespace k4\k4campaigns\models;


/**
 * Email message model class.
 *
 * @property string $fromEmail         The sender’s email address. Defaults to the System Email Address defined in
 *                                     Settings → Email.
 * @property string $fromName          The sender’s name. Defaults to the Sender Name defined in Settings → Email.
 * @property string $toEmail           The recipient’s email address. (Required)
 * @property string $toFirstName       The recipient’s first name.
 * @property string $toLastName        The recipient’s last name.
 * @property string $subject           The email’s subject. (Required)
 * @property string $body              The email’s plain text body. (Required)
 * @property string $htmlBody          The email’s HTML body.
 * @property string $replyTo           The Reply-To email address.
 * @property string $sender            The value that should be passed to the “MAIL FROM” SMTP header, if that differs
 *                                     from $fromEmail.
 * @property array  $cc                The recipients that should be CC’d on the email. Each element of this array
 *                                     should be a nested array containing the keys 'name' and 'email'.
 * @property array  $bcc               The recipients that should be BCC’d on the email. Each element of this array
 *                                     should be a nested array containing the keys 'name' and 'email'.
 * @property array  $stringAttachments Any strings of text which should be attached to the email as files. Each element
 *                                     of this array should be a nested array containing the keys 'string' (the contents
 *                                     of the file), 'fileName' (the name of the file), 'encoding' (the file encoding),
 *                                     and 'type' (the file’s MIME type).
 * @property array  $attachments       Any files which should be attached to the email. Each element of this array
 *                                     should be a nested array containing the keys 'path' (the path to the file),
 *                                     'name' (the name of the file), 'encoding' (the file encoding), and 'type' (the
 *                                     file’s MIME type).
 * @property array  $customHeaders     Any custom headers that should be included in the email. The keys of the array
 *                                     should identify the header names, and their values, well, take a guess.
 *
 * @author    Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @copyright Copyright (c) 2014, Pixel & Tonic, Inc.
 * @license   http://craftcms.com/license Craft License Agreement
 * @see       http://craftcms.com
 * @package   craft.app.models
 * @since     1.0
 */
class EmailModel extends BaseModel
{

}
