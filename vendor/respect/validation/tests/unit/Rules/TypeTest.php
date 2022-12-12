<?php

/*
 * This file is part of Respect/Validation.
 *
 * (c) Alexandre Gomes Gaigalas <alexandre@gaigalas.net>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

declare(strict_types=1);

namespace Respect\Validation\Rules;

use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Test\RuleTestCase;
use stdClass;

use function tmpfile;

/**
 * @group  rule
 *
 * @covers \Respect\Validation\Rules\Type
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 * @author Paul Karikari <paulkarikari1@gmail.com>
 */
final class TypeTest extends RuleTestCase
{
    /**
     * @test
     */
    public function shouldThrowExceptionWhenTypeIsNotValid(): void
    {
        $this->expectException(ComponentException::class);
        $this->expectExceptionMessageMatches('/"whatever" is not a valid type \(Available: .+\)/');

        new Type('whatever');
    }

    /**
     * {@inheritDoc}
     */
    public function providerForValidInput(): array
    {
        return [
            [new Type('array'), []],
            [new Type('bool'), true],
            [new Type('boolean'), false],
            [
                new Type('callable'),
                static function (): void {
                },
            ],
            [new Type('double'), 0.8],
            [new Type('float'), 1.0],
            [new Type('int'), 42],
            [new Type('integer'), 13],
            [new Type('null'), null],
            [new Type('object'), new stdClass()],
            [new Type('resource'), tmpfile()],
            [new Type('string'), 'Something'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function providerForInvalidInput(): array
    {
        return [
            [new Type('int'), '1'],
            [new Type('bool'), '1'],
        ];
    }
}
