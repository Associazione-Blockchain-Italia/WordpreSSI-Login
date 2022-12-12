--CREDITS--
Henrique Moody <henriquemoody@gmail.com>
--TEST--
getFullMessage() should include all validation messages in a chain
--FILE--
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;

try {
    Validator::stringType()->length(2, 15)->assert(0);
} catch (NestedValidationException $e) {
    echo $e->getFullMessage();
}
?>
--EXPECT--
- All of the required rules must pass for 0
  - 0 must be of type string
  - 0 must have a length between 2 and 15