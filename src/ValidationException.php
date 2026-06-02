<?php

/*
 * This file is part of the postal-code-validator package
 *
 * @author Joe Huss <detain@interserver.net>
 */

namespace Detain\ZipZapper;

/**
 * Exception thrown by Validator when an unsupported country code is supplied.
 *
 * Both isValid() and getFormats() throw this exception when the country code
 * is not present in the Validator::$formats registry.
 *
 * @author Joe Huss <detain@interserver.net>
 */
class ValidationException extends \Exception
{
}
