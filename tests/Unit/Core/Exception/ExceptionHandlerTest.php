<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopCommunity\Tests\Unit\Core\Exception;

use OxidEsales\Eshop\Core\Exception\ExceptionHandler;
use OxidEsales\Eshop\Core\Exception\StandardException;

class ExceptionHandlerTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    protected $message = 'TEST_EXCEPTION';

    public function testCallUnExistingMethod()
    {
        $this->expectException( \OxidEsales\Eshop\Core\Exception\SystemComponentException::class);
        $exceptionHandler = oxNew(\OxidEsales\Eshop\Core\Exception\ExceptionHandler::class);
        $exceptionHandler->__NotExistingFunction__();
    }

    public function testSetGetFileName()
    {
        $oTestObject = oxNew('oxexceptionhandler');
        $oTestObject->setLogFileName('TEST.log');
        $this->assertEquals('TEST.log', $oTestObject->getLogFileName());
    }

    /**
     * @dataProvider dataProviderExceptions Provides an OXID eShop style exception and a standard PHP Exception
     *
     * @param $exception
     */
    public function testExceptionHandlerReportsExceptionInDebugMode($exception)
    {
        $debug = true;

        $exceptionHandlerMock = $this->getMock(
            ExceptionHandler::class,
            ['displayDebugMessage'],
            [$debug]
        );
        $exceptionHandlerMock->expects($this->once())->method('displayDebugMessage');

        try {
            $exceptionHandlerMock->handleUncaughtException($exception);
        } catch (\Exception $e) {
            $this->fail('handleUncaughtException() throws an exception.');
        }

        $logFileContent = file_get_contents(OX_LOG_FILE);
        file_put_contents(OX_LOG_FILE, '');

        $this->assertContains($this->message, $logFileContent);
    }

    public function dataProviderExceptions()
    {
        return [
            [ new StandardException($this->message) ],
            [ new \Exception($this->message) ],
        ];
    }


    public function testSetIDebug()
    {
        $oTestObject = $this->getProxyClass("oxexceptionhandler");
        $oTestObject->setIDebug(2);
        //nothing should happen in unittests
        $this->assertEquals(2, $oTestObject->getNonPublicVar('_iDebug'));
    }

    /**
     * @covers \OxidEsales\Eshop\Core\Exception\ExceptionHandler::handleDatabaseException()
     */
    public function testHandleDatabaseExceptionDelegatesToHandleUncaughtException() {
        /** @var ExceptionHandler|\PHPUnit\Framework\MockObject\MockObject $exceptionHandlerMock */
        $exceptionHandlerMock = $this->getMock(ExceptionHandler::class, ['handleUncaughtException']);
        $exceptionHandlerMock->expects($this->once())->method('handleUncaughtException');

        $databaseException = oxNew(\OxidEsales\Eshop\Core\Exception\DatabaseException::class, 'message', 0, new \Exception());

        $exceptionHandlerMock->handleDatabaseException($databaseException);
    }

    /**
     * @dataProvider dataProviderTestHandleUncaughtExceptionDebugStatus
     *
     * @param $debug
     */
    public function testHandleUncaughtExceptionWillAlwaysWriteToLogFile($debug)
    {
        /** @var ExceptionHandler|\PHPUnit_Framework_MockObject_MockObject $exceptionHandlerMock */
        $exceptionHandlerMock = $this->getMock(
            ExceptionHandler::class,
            ['writeExceptionToLog','displayOfflinePage','displayDebugMessage'],
            [$debug]
        );
        $exceptionHandlerMock->expects($this->once())->method('writeExceptionToLog');

        $exceptionHandlerMock->handleUncaughtException(new \Exception());
    }

    /**
     * The message is different, if in CLI mode.
     * Real message cannot be tested in UNIT or Integration tests
     *
     * @dataProvider dataProviderTestHandleUncaughtExceptionDebugStatus
     *
     * @covers \OxidEsales\Eshop\Core\Exception\ExceptionHandler::handleUncaughtException
     */
    public function testHandleUncaughtExceptionWillDisplayShortDebugMessageInCliMode($debug) {
        /** @var ExceptionHandler|\PHPUnit_Framework\MockObject\MockObject $exceptionHandlerMock */
        $exceptionHandlerMock = $this->getMock(
            ExceptionHandler::class,
            ['writeExceptionToLog'],
            [$debug]
        );

        $exceptionHandlerMock->expects($this->any())->method('writeExceptionToLog')->willReturn(true);
        ob_start();
        $exceptionHandlerMock->handleUncaughtException(new \Exception());
        $displayMessage = ob_get_clean();

        $this->assertContains('Uncaught exception. See error log for more information.', $displayMessage);
    }

    /**
     * @covers \OxidEsales\Eshop\Core\Exception\ExceptionHandler::handleUncaughtException
     */
    public function testHandleUncaughtExceptionWillDisplayDebugMessageIfDebugIsTrue() {
        $debug = true;
        /** @var ExceptionHandler|\PHPUnit_Framework_MockObject_MockObject $exceptionHandlerMock */
        $exceptionHandlerMock = $this->getMock(
            ExceptionHandler::class,
            ['writeExceptionToLog','displayDebugMessage'],
            [$debug]
        );
        $exceptionHandlerMock->expects($this->once())->method('displayDebugMessage');

        $exceptionHandlerMock->handleUncaughtException(new \Exception());
    }

    /**
     * @covers \OxidEsales\Eshop\Core\Exception\ExceptionHandler::handleUncaughtException
     */
    public function testHandleUncaughtExceptionWillDisplayOfflinePageIfDebugIsFalse() {
        $debug = false;
        /** @var ExceptionHandler|\PHPUnit\Framework\MockObject\MockObject $exceptionHandlerMock */
        $exceptionHandlerMock = $this->getMock(
            ExceptionHandler::class,
            ['writeExceptionToLog','displayOfflinePage'],
            [$debug]
        );
        $exceptionHandlerMock->expects($this->once())->method('displayOfflinePage');

        $exceptionHandlerMock->handleUncaughtException(new \Exception());
    }

    /**
     * Data provider for testHandleUncaughtExceptionWillExitApplication
     *
     * @return array
     */
    public function dataProviderTestHandleUncaughtExceptionDebugStatus ()
    {
        return [
            ['debug' => true],
            ['debug' => false],
        ];
    }

    /**
     * @covers \OxidEsales\Eshop\Core\Exception\ExceptionHandler::getLogFileName()
     */
    public function testGetLogFileNameReturnsBaseNameOfLogeFile()
    {
        /** @var ExceptionHandler $exceptionHandlerMock */
        $exceptionHandler = oxNew(ExceptionHandler::class);

        $actualLogFileName = $exceptionHandler->getLogFileName();
        $expectedLogFileName = basename($actualLogFileName);

        $this->assertEquals($expectedLogFileName, $actualLogFileName, 'getLogFileName returns basename of logFile');
    }
}
