<?php
declare(strict_types=1);

namespace UnitTest\Pipeline;
use Codeception\Test\Unit;
use Laminas\ServiceManager\ServiceManager;
use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Selami\Resources\Pipelines\Adder;
use Selami\Resources\Pipelines\Multiplier;
use Selami\Stdlib\Pipeline\InvalidStageException;
use Selami\Stdlib\Pipeline\Pipeline;
use Selami\Stdlib\Pipeline\StageDoesNotExistException;

class PipelineTest extends Unit
{
    protected $tester;
    private ContainerInterface $serviceManager;

    protected function _before(): void
    {
        $this->serviceManager = new ServiceManager([
            'factories' => [
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
        $pipeline = Pipeline::withContainer($this->serviceManager)
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