<?php
declare(strict_types=1);

namespace UnitTest\Pipeline;
use Codeception\Test\Unit;
use Laminas\ServiceManager\ServiceManager;
use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Backendbase\Resources\Pipelines\Adder;
use Backendbase\Resources\Pipelines\Multiplier;
use Backendbase\Utility\Pipeline\InvalidStageException;
use Backendbase\Utility\Pipeline\Pipeline;
use Backendbase\Utility\Pipeline\PipelineInterface;
use Backendbase\Utility\Pipeline\StageDoesNotExistException;
use Backendbase\Utility\Pipeline\PipelineFactory;
class PipelineTest extends Unit
{
    protected $tester;
    private ContainerInterface $serviceManager;

    protected function _before(): void
    {
        $this->serviceManager = new ServiceManager([
            'factories' => [
                PipelineInterface::class => PipelineFactory::class,
                Adder::class => InvokableFactory::class,
            ],
        ]);
    }

    protected function _after(): void
    {
    }

    /**
     * @test
     */
    public function shouldSuccessfullyCalculateUsingPipes() : void
    {
        $pipeline = $this->serviceManager->get(PipelineInterface::class)
            ->pipe(Adder::class)
            ->pipe(new Multiplier(2));

        $result = $pipeline->process(['firstNumber' => 2, 'secondNumber' => 3]);
        $this->assertSame(10, $result['result']);
    }

    /**
     * @test
     */
    public function shouldFailCalculateUsingContainerPipes() : void
    {
        $this->expectException(StageDoesNotExistException::class);
        Pipeline::withContainer($this->serviceManager)
            ->pipe(Adder::class)
            ->pipe(Multiplier::class);
    }

    /**
     * @test
     */
    public function shouldFailCalculateUsingCallablePipes() : void
    {
        $this->expectException(InvalidStageException::class);
        Pipeline::withContainer($this->serviceManager)
            ->pipe(Adder::class)
            ->pipe(function($payload) { return $payload; });
    }
}