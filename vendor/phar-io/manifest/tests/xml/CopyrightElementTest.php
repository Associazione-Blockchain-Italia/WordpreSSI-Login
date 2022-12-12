<?php declare(strict_types = 1);
namespace PharIo\Manifest;

use DOMDocument;

class CopyrightElementTest extends \PHPUnit\Framework\TestCase {
    /** @var DOMDocument */
    private $dom;

    /** @var CopyrightElement */
    private $copyright;

    protected function setUp(): void {
        $this->dom = new DOMDocument();
        $this->dom->loadXML('<?xml version="1.0" ?><copyright xmlns="https://phar.io/xml/manifest/1.0" />');
        $this->copyright = new CopyrightElement($this->dom->documentElement);
    }

    public function testThrowsExceptionWhenGetAuthroElementsIsCalledButNodesAreMissing(): void {
        $this->expectException(ManifestElementException::class);
        $this->copyright->getAuthorElements();
    }

    public function testThrowsExceptionWhenGetLicenseElementIsCalledButNodeIsMissing(): void {
        $this->expectException(ManifestElementException::class);
        $this->copyright->getLicenseElement();
    }

    public function testGetAuthorElementsReturnsAuthorElementCollection(): void {
        $this->dom->documentElement->appendChild(
            $this->dom->createElementNS('https://phar.io/xml/manifest/1.0', 'author')
        );
        $this->assertInstanceOf(
            AuthorElementCollection::class,
            $this->copyright->getAuthorElements()
        );
    }

    public function testGetLicenseElementReturnsLicenseElement(): void {
        $this->dom->documentElement->appendChild(
            $this->dom->createElementNS('https://phar.io/xml/manifest/1.0', 'license')
        );
        $this->assertInstanceOf(
            LicenseElement::class,
            $this->copyright->getLicenseElement()
        );
    }
}
