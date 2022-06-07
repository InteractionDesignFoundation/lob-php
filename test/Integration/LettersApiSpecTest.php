<?php
/**
 * LettersApiSpecTest
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
use \OpenAPI\Client\ApiException;
use PHPUnit\Framework\TestCase;
use \OpenAPI\Client\Model\LetterEditable;
use \OpenAPI\Client\Model\PostcardSize;
use \OpenAPI\Client\Api\LettersApi;
use \OpenAPI\Client\Model\AddressEditable;
use \OpenAPI\Client\Model\MailType;
use \OpenAPI\Client\Api\AddressesApi;

/**
 * AddressesApiTest Class Doc Comment
 *
 * @category Class
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */

class LettersApiSpecTest extends TestCase
{
    /**
     * Setup before running any test cases
     */
    private static $config;
    private static $addressApi;
    private static $letterApi;
    private static $editableLetter;
    private static $regularLetter;
    private static $certifiedLetter;
    private static $registeredLetter;
    private static $errorLetter;

    // for teardown post-testing
    private $idsForCleanup = [];
    private static $toAddress;
    private static $fromAddress;
    private static $toAddress2;
    private static $fromAddress2;

    // set up constant fixtures
    public static function setUpBeforeClass(): void
    {
        // create instance of AddressesApi & addresses to use for other tests
        self::$config = new Configuration();
        self::$config->setApiKey('basic', getenv('LOB_API_TEST_KEY'));
        self::$addressApi = new AddressesApi(self::$config);

        $address1 = new AddressEditable();
        $address1->setName("THING T. THING");
        $address1->setAddressLine1("1313 CEMETERY LN");
        $address1->setAddressCity("WESTFIELD");
        $address1->setAddressState("NJ");
        $address1->setAddressZip("07000");

        $address2 = new AddressEditable();
        $address2->setName("FESTER");
        $address2->setAddressLine1("001 CEMETERY LN");
        $address2->setAddressLine2("SUITE 666");
        $address2->setAddressCity("WESTFIELD");
        $address2->setAddressState("NJ");
        $address2->setAddressZip("07000");

        self::$toAddress = self::$addressApi->create($address1);
        self::$fromAddress = self::$addressApi->create($address2);

        $address3 = new AddressEditable();
        $address3->setName("MORTICIA ADDAMS");
        $address3->setAddressLine1("1212 CEMETERY LN");
        $address3->setAddressCity("WESTFIELD");
        $address3->setAddressState("NJ");
        $address3->setAddressZip("07000");

        $address4 = new AddressEditable();
        $address4->setName("COUSIN ITT");
        $address4->setAddressLine1("1515 CEMETERY LN");
        $address4->setAddressLine2("FLOOR 0");
        $address4->setAddressCity("WESTFIELD");
        $address4->setAddressState("NJ");
        $address4->setAddressZip("07000");

        self::$toAddress2 = self::$addressApi->create($address3);
        self::$fromAddress2 = self::$addressApi->create($address4);

        // create new instance of LettersApi to use in other tests
        self::$letterApi = new LettersApi(self::$config);

        // create regular letter
        self::$regularLetter = new LetterEditable();
        self::$regularLetter->setTo(self::$toAddress->getId());
        self::$regularLetter->setFrom(self::$fromAddress->getId());
        self::$regularLetter->setDescription("Dummy Letter (Integration Test)");
        self::$regularLetter->setFile("https://s3-us-west-2.amazonaws.com/public.lob.com/assets/us_letter_1pg.pdf");
        self::$regularLetter->setColor(true);

        // create certified letter
        self::$certifiedLetter = new LetterEditable();
        self::$certifiedLetter->setTo(self::$toAddress->getId());
        self::$certifiedLetter->setFrom(self::$fromAddress->getId());
        self::$certifiedLetter->setExtraService(LetterEditable::EXTRA_SERVICE_CERTIFIED);
        self::$certifiedLetter->setDescription("Dummy Letter (Integration Test)");
        self::$certifiedLetter->setFile("https://s3-us-west-2.amazonaws.com/public.lob.com/assets/us_letter_1pg.pdf");
        self::$certifiedLetter->setColor(true);

        // create registered letter
        self::$registeredLetter = new LetterEditable();
        self::$registeredLetter->setTo(self::$toAddress2->getId());
        self::$registeredLetter->setFrom(self::$fromAddress2->getId());
        self::$registeredLetter->setExtraService(LetterEditable::EXTRA_SERVICE_REGISTERED);
        self::$registeredLetter->setDescription("Dummy Letter (Integration Test)");
        self::$registeredLetter->setFile("https://s3-us-west-2.amazonaws.com/public.lob.com/assets/us_letter_1pg.pdf");
        self::$registeredLetter->setColor(true);

        // create error letter
        self::$errorLetter = new LetterEditable();
        self::$errorLetter->setFrom(self::$fromAddress->getId());
        self::$errorLetter->setExtraService(LetterEditable::EXTRA_SERVICE_REGISTERED);
        self::$errorLetter->setDescription("Dummy Letter (Integration Test)");
        self::$errorLetter->setFile("https://s3-us-west-2.amazonaws.com/public.lob.com/assets/us_letter_1pg.pdf");
        self::$errorLetter->setColor(true);
    }

    public function tearDown(): void
    {
        foreach ($this->idsForCleanup as $id) {
            self::$letterApi->cancel($id);
        }
    }

    public static function tearDownAfterClass(): void {
        self::$addressApi->delete(self::$toAddress->getId());
        self::$addressApi->delete(self::$toAddress2->getId());
        self::$addressApi->delete(self::$fromAddress->getId());
        self::$addressApi->delete(self::$fromAddress2->getId());
    }

    // include static cleanup for all the addresses?

    public function testLettersApiInstantiation200() {
        try {
            $lettersApi200 = new LettersApi(self::$config);
            $this->assertEquals(gettype($lettersApi200), 'object');
        } catch (Exception $instantiationError) {
            echo 'Caught exception: ',  $instantiationError->getMessage(), "\n";
        }
    }

    public function testCreateRegular200()
    {
        try {
            $createdLetter = self::$letterApi->create(self::$regularLetter);
            $this->assertMatchesRegularExpression('/ltr_/', $createdLetter->getId());
            array_push($this->idsForCleanup, $createdLetter->getId());
        } catch (Exception $createError) {
            echo 'Caught exception: ',  $createError->getMessage(), "\n";
        }
    }

    public function testCreateCertified200()
    {
        try {
            $createdLetter = self::$letterApi->create(self::$certifiedLetter);
            $this->assertMatchesRegularExpression('/ltr_/', $createdLetter->getId());
            array_push($this->idsForCleanup, $createdLetter->getId());
        } catch (Exception $createError) {
            echo 'Caught exception: ',  $createError->getMessage(), "\n";
        }
    }

    public function testCreateRegistered200()
    {
        try {
            $createdLetter = self::$letterApi->create(self::$registeredLetter);
            $this->assertMatchesRegularExpression('/ltr_/', $createdLetter->getId());
            array_push($this->idsForCleanup, $createdLetter->getId());
        } catch (Exception $createError) {
            echo 'Caught exception: ',  $createError->getMessage(), "\n";
        }
    }

    // does not include required field in request
    public function testCreate422()
    {
        try {
            $this->expectException(ApiException::class);
            $this->expectExceptionMessageMatches("/to is required/");
            $errorResponse = self::$letterApi->create(self::$errorLetter);
        } catch (Exception $createError) {
            echo 'Caught exception: ',  $createError->getMessage(), "\n";
        }
    }

    // uses a bad key to attempt to send a request
    public function testLetterApi401() {
        try {
            $wrongConfig = new Configuration();
            $wrongConfig->setApiKey('basic', 'BAD KEY');
            $letterApiError = new LettersApi($wrongConfig);

            $this->expectException(ApiException::class);
            $this->expectExceptionMessageMatches("/Your API key is not valid. Please sign up on lob.com to get a valid api key./");
            $errorResponse = $letterApiError->create(self::$regularLetter);
        } catch (Exception $instantiationError) {
            echo 'Caught exception: ',  $instantiationError->getMessage(), "\n";
        }
    }

    public function testGet200()
    {
        try {
            $createdLetter = self::$letterApi->create(self::$regularLetter);
            $retrievedLetter = self::$letterApi->get($createdLetter->getId());
            $this->assertEquals($createdLetter->getTo(), $retrievedLetter->getTo());
            array_push($this->idsForCleanup, $createdLetter->getId());
        } catch (Exception $retrieveError) {
            echo 'Caught exception: ',  $retrieveError->getMessage(), "\n";
        }
    }

    public function testGet404()
    {
        try {
            $this->expectException(ApiException::class);
            $this->expectExceptionMessageMatches("/letter not found/");
            $badRetrieval = self::$letterApi->get("ltr_NONEXISTENT");
        } catch (Exception $retrieveError) {
            echo 'Caught exception: ',  $retrieveError->getMessage(), "\n";
        }
    }

    public function testList200()
    {
        $nextUrl = "";
        $previousUrl = "";
        try {
            $ltr1 = self::$letterApi->create(self::$regularLetter);
            $ltr2 = self::$letterApi->create(self::$registeredLetter);
            $listedLetters = self::$letterApi->list(2);
            $this->assertGreaterThan(1, count($listedLetters->getData()));
            $this->assertLessThanOrEqual(2, count($listedLetters->getData()));
            $nextUrl = substr($listedLetters->getNextUrl(), strrpos($listedLetters->getNextUrl(), "after=") + 6);
            $this->assertIsString($nextUrl);
            array_push($this->idsForCleanup, $ltr1->getId());
            array_push($this->idsForCleanup, $ltr2->getId());
        } catch (Exception $listError) {
            echo 'Caught exception: ',  $listError->getMessage(), "\n";
        }

        // response using nextUrl
        if ($nextUrl != "") {
            try {
                $ltr1 = self::$letterApi->create(self::$regularLetter);
                $ltr2 = self::$letterApi->create(self::$registeredLetter);
                $listedLettersAfter = self::$letterApi->list(2, null, $nextUrl);
                $this->assertGreaterThan(1, count($listedLettersAfter->getData()));
                $this->assertLessThanOrEqual(2, count($listedLettersAfter->getData()));
                $previousUrl = substr($listedLettersAfter->getPreviousUrl(), strrpos($listedLettersAfter->getPreviousUrl(), "before=") + 7);
                $this->assertIsString($previousUrl);
                array_push($this->idsForCleanup, $ltr1->getId());
                array_push($this->idsForCleanup, $ltr2->getId());
            } catch (Exception $listError) {
                echo 'Caught exception: ',  $listError->getMessage(), "\n";
            }
        }

        // response using previousUrl
        if ($previousUrl != "") {
            try {
                $ltr1 = self::$letterApi->create(self::$regularLetter);
                $ltr2 = self::$letterApi->create(self::$registeredLetter);
                $listedLettersBefore = self::$letterApi->list(2, $previousUrl);
                $this->assertGreaterThan(1, count($listedLettersBefore->getData()));
                $this->assertLessThanOrEqual(2, count($listedLettersBefore->getData()));
                array_push($this->idsForCleanup, $ltr1->getId());
                array_push($this->idsForCleanup, $ltr2->getId());
            } catch (Exception $listError) {
                echo 'Caught exception: ',  $listError->getMessage(), "\n";
            }
        }
    }

    public function testCancel200()
    {
        try {
            $createdLetter = self::$letterApi->create(self::$regularLetter);
            $deletedLetter = self::$letterApi->cancel($createdLetter->getId());
            $this->assertEquals(true, $deletedLetter->getDeleted());
        } catch (Exception $deleteError) {
            echo 'Caught exception: ',  $deleteError->getMessage(), "\n";
        }
    }

    public function testCancel404()
    {
        try {
            $this->expectException(ApiException::class);
            $this->expectExceptionMessageMatches("/letter not found/");
            $badDeletion = self::$letterApi->cancel("ltr_NONEXISTENT");
        } catch (Exception $retrieveError) {
            echo 'Caught exception: ',  $retrieveError->getMessage(), "\n";
        }
    }
}