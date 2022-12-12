<?php

namespace Firebase\JWT;

use PHPUnit\Framework\TestCase;

class JWKTest extends TestCase
{
    private static $keys;
    private static $privKey1;
    private static $privKey2;

    public function testMissingKty()
    {
        $this->setExpectedException(
            'UnexpectedValueException',
            'JWK must contain a "kty" parameter'
        );

        $badJwk = array('kid' => 'foo');
        $keys = JWK::parseKeySet(array('keys' => array($badJwk)));
    }

    public function testInvalidAlgorithm()
    {
        $this->setExpectedException(
            'UnexpectedValueException',
            'No supported algorithms found in JWK Set'
        );

        $badJwk = array('kty' => 'BADALG');
        $keys = JWK::parseKeySet(array('keys' => array($badJwk)));
    }

    public function testParsePrivateKey()
    {
        $this->setExpectedException(
            'UnexpectedValueException',
            'RSA private keys are not supported'
        );

        $jwkSet = json_decode(
            file_get_contents(__DIR__ . '/data/rsa-jwkset.json'),
            true
        );
        $jwkSet['keys'][0]['d'] = 'privatekeyvalue';

        JWK::parseKeySet($jwkSet);
    }

    public function testParsePrivateKeyWithoutAlg()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'JWK key is missing "alg"'
        );

        $jwkSet = json_decode(
            file_get_contents(__DIR__ . '/data/rsa-jwkset.json'),
            true
        );
        unset($jwkSet['keys'][0]['alg']);

        JWK::parseKeySet($jwkSet);
    }

    public function testParseKeyWithEmptyDValue()
    {
        $jwkSet = json_decode(
            file_get_contents(__DIR__ . '/data/rsa-jwkset.json'),
            true
        );

        // empty or null values are ok
        $jwkSet['keys'][0]['d'] = null;

        $keys = JWK::parseKeySet($jwkSet);
        $this->assertTrue(is_array($keys));
    }

    public function testParseJwkKeySet()
    {
        $jwkSet = json_decode(
            file_get_contents(__DIR__ . '/data/rsa-jwkset.json'),
            true
        );
        $keys = JWK::parseKeySet($jwkSet);
        $this->assertTrue(is_array($keys));
        $this->assertArrayHasKey('jwk1', $keys);
        self::$keys = $keys;
    }

    public function testParseJwkKey_empty()
    {
        $this->setExpectedException('InvalidArgumentException', 'JWK must not be empty');

        JWK::parseKeySet(array('keys' => array(array())));
    }

    public function testParseJwkKeySet_empty()
    {
        $this->setExpectedException('InvalidArgumentException', 'JWK Set did not contain any keys');

        JWK::parseKeySet(array('keys' => array()));
    }

    /**
     * @depends testParseJwkKeySet
     */
    public function testDecodeByJwkKeySetTokenExpired()
    {
        $privKey1 = file_get_contents(__DIR__ . '/data/rsa1-private.pem');
        $payload = array('exp' => strtotime('-1 hour'));
        $msg = JWT::encode($payload, $privKey1, 'RS256', 'jwk1');

        $this->setExpectedException('Firebase\JWT\ExpiredException');

        JWT::decode($msg, self::$keys, array('RS256'));
    }

    /**
     * @depends testParseJwkKeySet
     */
    public function testDecodeByJwkKeySet()
    {
        $privKey1 = file_get_contents(__DIR__ . '/data/rsa1-private.pem');
        $payload = array('sub' => 'foo', 'exp' => strtotime('+10 seconds'));
        $msg = JWT::encode($payload, $privKey1, 'RS256', 'jwk1');

        $result = JWT::decode($msg, self::$keys, array('RS256'));

        $this->assertEquals("foo", $result->sub);
    }

    /**
     * @depends testParseJwkKeySet
     */
    public function testDecodeByMultiJwkKeySet()
    {
        $privKey2 = file_get_contents(__DIR__ . '/data/rsa2-private.pem');
        $payload = array('sub' => 'bar', 'exp' => strtotime('+10 seconds'));
        $msg = JWT::encode($payload, $privKey2, 'RS256', 'jwk2');

        $result = JWT::decode($msg, self::$keys, array('RS256'));

        $this->assertEquals("bar", $result->sub);
    }

    /*
     * For compatibility with PHPUnit 4.8 and PHP < 5.6
     */
    public function setExpectedException($exceptionName, $message = '', $code = null)
    {
        if (method_exists($this, 'expectException')) {
            $this->expectException($exceptionName);
            if ($message) {
                $this->expectExceptionMessage($message);
            }
        } else {
            parent::setExpectedException($exceptionName, $message, $code);
        }
    }
}
