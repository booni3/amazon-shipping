<?php

namespace Booni3\AmazonShipping\Tests;

use Booni3\AmazonShipping\AmazonShipping;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Orchestra\Testbench\TestCase;
use Booni3\AmazonShipping\AmazonShippingServiceProvider;

class AmazonShippingTest extends TestCase
{
    use ShipmentCreatorStub;

    /** @var Client  */
    protected $client;

    /** @var MockHandler  */
    protected $mock;

    /** @var array  */
    protected $config = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->config = [
            'accountNumber' => 12345678,
            'environment' => 'production',
            'secret' => 'xxxxxxxxxxxx',
            'client_id' => 'amzn1.application-oa2-client.xxxxxxxxxxxxxxxxxxx',
            'timeout' => null
        ];

        $this->mock = new MockHandler([]);

        $this->mock->append(new Response(200, [], file_get_contents(__DIR__.'/stubs/Auth.json')));

        $handlerStack = HandlerStack::create($this->mock);

        $this->client = new Client(['handler' => $handlerStack]);
    }

    protected function getPackageProviders($app)
    {
        return [AmazonShippingServiceProvider::class];
    }

    /** @test */
    public function it_can_get_eligible_services()
    {
        $api = AmazonShipping::make($this->config, $this->client);

        $this->mock->append(new Response(200, [], file_get_contents(__DIR__.'/stubs/EligibleServices.json')));

        $res = $api->shipment()->getServiceOfferings($this->getTestCreatorRealAddress());

        $this->assertArrayHasKey('eligibleServiceOfferings', $res);
    }

    /** @test */
    public function it_can_create_a_shipment()
    {
        $api = AmazonShipping::make($this->config, $this->client);

        $this->mock->append(new Response(200, [], file_get_contents(__DIR__.'/stubs/CreateShipment.json')));

        $res = $api->shipment()->create($this->getTestCreatorRealAddress());

        $this->assertArrayHasKey('shipmentId', $res);
    }

    /** @test */
    public function it_can_confirm_a_shipment()
    {
        $api = AmazonShipping::make($this->config, $this->client);

        $this->mock->append(new Response(200, [], file_get_contents(__DIR__.'/stubs/ConfirmShipment.json')));

        $res = $api->shipment()->confirm(
            97688778583701,
            'a6b338d26db2acd5c246ca42ebce4cd09a05fce6f231d6dcc699510ea8b9a9a41600248064148'
        );

        $this->assertArrayHasKey('shipmentId', $res);
    }

    /** @test */
    public function it_can_cancel_a_shipment()
    {
        $api = AmazonShipping::make($this->config, $this->client);

        $this->mock->append(new Response(200, [], file_get_contents(__DIR__.'/stubs/CancelShipment.json')));

        $res = $api->shipment()->cancel(97688778583701);

        $this->assertEquals($res, []);
    }

    /** @test */
    public function it_can_get_a_shipment()
    {
        $api = AmazonShipping::make($this->config, $this->client);

        $this->mock->append(new Response(200, [], file_get_contents(__DIR__.'/stubs/GetShipment.json')));

        $res = $api->shipment()->getShipment(97688778583701);

        $this->assertArrayHasKey('shipmentId', $res);
    }

    /** @test */
    public function it_can_get_a_label()
    {
        $api = AmazonShipping::make($this->config, $this->client);

        $this->mock->append(new Response(200, [], file_get_contents(__DIR__.'/stubs/GetLabelZpl300.json')));

        $res = $api->shipment()->getLabel(
            97688778583701,
            'A10658913053',
            'zpl300'
        );

        $this->assertArrayHasKey('formatType', $res);
        $this->assertTrue($res['formatType'] == 'ZPL');
    }

    /** @test */
    public function it_can_get_container_status()
    {
        $api = AmazonShipping::make($this->config, $this->client);

        $this->mock->append(new Response(200, [], file_get_contents(__DIR__.'/stubs/ContainerStatus.json')));

        $res = $api->shipment()->getContainerStatus(97688778583701);

        $this->assertArrayHasKey('containerStatusList', $res);
    }

}
