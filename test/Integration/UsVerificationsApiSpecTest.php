<?php
/**
 * UsVerificationsApiSpecTest
 * PHP version 7.3
 *
 * @category Class
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */

/**
 * Lob
 *
 * The Lob API is organized around REST. Our API is designed to have predictable, resource-oriented URLs and uses HTTP response codes to indicate any API errors. <p> Looking for our [previous documentation](https://lob.github.io/legacy-docs/)?
 *
 * The version of the OpenAPI document: 1.3.0
 * Contact: lob-openapi@lob.com
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 5.2.1
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Please update the test case below to test the endpoint.
 */

namespace OpenAPI\Client\Test\Api;

use \OpenAPI\Client\Configuration;
use PHPUnit\Framework\TestCase;
use \OpenAPI\Client\Api\UsVerificationsApi;
use \OpenAPI\Client\Model\UsVerificationsWritable;
use \OpenAPI\Client\Model\MultipleComponentsList;
use \OpenAPI\Client\Model\MultipleComponents;

/**
 * UsVerificationsApiSpecTest Class Doc Comment
 *
 * @category Class
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */

class UsVerificationsApiSpecTest extends TestCase
{
    /**
     * Setup before running any test cases
     */
    private static $config;
    private static $usvApi200;
    private static $invalidUsvApi;
    private static $validAddress1;
    private static $validAddress2;
    private static $multipleAddressList;
    private static $undeliverableAddress;

    // set up constant fixtures
    public static function setUpBeforeClass(): void
    {
        // create instance of UsVerificationsApiSpecTest & an editable address for other tests
        self::$config = new Configuration();
        self::$config->setApiKey("basic", getenv("LOB_API_LIVE_KEY"));
        self::$usvApi200 = new UsVerificationsApi(self::$config);

        $wrongConfig = new Configuration();
        $wrongConfig->setApiKey("basic", "BAD KEY");
        self::$invalidUsvApi = new UsVerificationsApi($wrongConfig);

        self::$validAddress1 = new UsVerificationsWritable();
        self::$validAddress1->setPrimaryLine("210 KING ST");
        self::$validAddress1->setCity("SAN FRANCISCO");
        self::$validAddress1->setState("CA");
        self::$validAddress1->setZipCode("94107");

        self::$validAddress2 = new UsVerificationsWritable();
        self::$validAddress2->setPrimaryLine("001 CEMETERY LN");
        self::$validAddress2->setSecondaryLine("SUITE 666");
        self::$validAddress2->setCity("WESTFIELD");
        self::$validAddress2->setState("NJ");
        self::$validAddress2->setZipCode("07000");

        $mc1 = new MultipleComponents();
        $mc1->setPrimaryLine("210 KING ST");
        $mc1->setCity("SAN FRANCISCO");
        $mc1->setState("CA");
        $mc1->setZipCode("94107");

        $mc2 = new MultipleComponents();
        $mc2->setPrimaryLine("001 CEMETERY LN");
        $mc2->setSecondaryLine("SUITE 666");
        $mc2->setCity("WESTFIELD");
        $mc2->setState("NJ");
        $mc2->setZipCode("07000");

        // multiple components list for bulk verification test
        self::$multipleAddressList = new MultipleComponentsList();
        self::$multipleAddressList->setAddresses([$mc1, $mc2]);

        self::$undeliverableAddress = new UsVerificationsWritable();
        self::$undeliverableAddress->setPrimaryLine("1 CEMETERY ST");
        self::$undeliverableAddress->setCity("POTATOFIELD");
        self::$undeliverableAddress->setState("NJ");
        self::$undeliverableAddress->setZipCode("07000");
    }

    /**
     * @group integration
     * @group usVerifications
     */
    public function testUsVerificationsApiInstantiation200() {
        try {
            $usvApi200 = new UsVerificationsApi(self::$config);
            $this->assertEquals(gettype($usvApi200), "object");
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @group integration
     * @group usVerifications
     */
    public function testSingleUsVerificationDeliverable()
    {
        try {
            $usVerificationObject = self::$usvApi200->verifySingle(self::$validAddress1);
            $this->assertMatchesRegularExpression("/us_ver_/", $usVerificationObject->getId());
            $this->assertEquals("deliverable", $usVerificationObject->getDeliverability());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @group integration
     * @group usVerifications
     */
    public function testSingleUsVerificationUndeliverable()
    {
        try {
        $usVerificationObject = self::$usvApi200->verifySingle(self::$undeliverableAddress);
        $this->assertMatchesRegularExpression("/us_ver_/", $usVerificationObject->getId());
        $this->assertEquals("undeliverable", $usVerificationObject->getDeliverability());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function testSingleUsVerification0()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches("/Missing the required parameter/");
        $usVerificationObject = self::$usvApi200->verifySingle(null);
    }

    public function testSingleUsVerification401()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches("/Your API key is not valid/");
        $badVerification = self::$invalidUsvApi->verifySingle(self::$validAddress1);
    }

    public function testBulkUsVerificationValid()
    {
        try {
        $usVerificationObject = self::$usvApi200->verifyBulk(self::$multipleAddressList);
        $this->assertGreaterThan(1, count($usVerificationObject->getAddresses()));
        $this->assertEquals("deliverable", $usVerificationObject->getAddresses()[0]->getDeliverability());
        $this->assertEquals("undeliverable", $usVerificationObject->getAddresses()[1]->getDeliverability());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @group integration
     * @group usVerifications
     */
    public function testBulkUsVerificationError()
    {
        try {
            $mc1 = new MultipleComponents();
            $mc1->setPrimaryLine("210 KING ST");
            $mc1->setCity("SAN FRANCISCO");
            $mc1->setState("CA");
            $mc1->setZipCode("94107");

            // second entry has no primary line, should error
            $mc2 = new MultipleComponents();
            $mc2->setSecondaryLine("SUITE 666");
            $mc2->setCity("WESTFIELD");
            $mc2->setState("NJ");
            $mc2->setZipCode("07000");

            // multiple components list for bulk verification test
            $errorAddressList = new MultipleComponentsList();
            $errorAddressList->setAddresses([$mc1, $mc2]);

            $errorVerificationObject = self::$usvApi200->verifyBulk($errorAddressList);

            $this->assertEquals("primary_line is required or address is required", $errorVerificationObject->getAddresses()[1]->getError()->getError()->getMessage());
            $this->assertEquals(1, $errorVerificationObject->getErrors());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function testBulkUsVerification0()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches("/Missing the required parameter/");
        $usVerificationObject = self::$usvApi200->verifyBulk(null);
    }

    public function testBulkUsVerification401()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches("/Your API key is not valid/");
        $badVerification = self::$invalidUsvApi->verifyBulk(self::$multipleAddressList);
    }
}
