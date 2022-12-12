--CREDITS--
Emmerson Siqueira <emmersonsiqueira@gmail.com>
Henrique Moody <henriquemoody@gmail.com>
--FILE--
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use Respect\Validation\Validator as v;

date_default_timezone_set('UTC');

$user = new stdClass();
$user->name = 'Alexandre';
$user->birthdate = '1987-07-01';

$userValidator = v::attribute('name', v::stringType()->length(1, 32))
                  ->attribute('birthdate', v::dateTime()->minAge(18));

$userValidator->assert($user);

echo 'Nothing to fail';
?>
--EXPECT--
Nothing to fail
