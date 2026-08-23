<?php

namespace Tests\Feature;

use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class WhatsAppServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Set up default config values for testing
        Config::set('services.whatsapp.to', '+923000000000');
    }

    public function test_send_via_log_driver(): void
    {
        Config::set('services.whatsapp.driver', 'log');

        Log::shouldReceive('info')
            ->once()
            ->withArgs(function ($message) {
                return str_contains($message, 'WhatsApp Notification (Driver: log) to +923000000000') 
                    && str_contains($message, 'Test Log Message');
            });

        $service = new WhatsAppService();
        $result = $service->send('+923000000000', 'Test Log Message');

        $this->assertTrue($result);
    }

    public function test_send_via_twilio_driver_success(): void
    {
        Config::set('services.whatsapp.driver', 'twilio');
        Config::set('services.whatsapp.twilio.sid', 'ACXXXXXX');
        Config::set('services.whatsapp.twilio.token', 'tokenXXXXXX');
        Config::set('services.whatsapp.twilio.from', '+14155238886');

        Http::fake([
            'https://api.twilio.com/*' => Http::response(['status' => 'queued'], 201)
        ]);

        $service = new WhatsAppService();
        $result = $service->send('+923000000000', 'Test Twilio Message');

        $this->assertTrue($result);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.twilio.com/2010-04-01/Accounts/ACXXXXXX/Messages.json'
                && $request['To'] === 'whatsapp:+923000000000'
                && $request['From'] === 'whatsapp:+14155238886'
                && $request['Body'] === 'Test Twilio Message';
        });
    }

    public function test_send_via_ultramsg_driver_success(): void
    {
        Config::set('services.whatsapp.driver', 'ultramsg');
        Config::set('services.whatsapp.ultramsg.instance_id', 'instance123');
        Config::set('services.whatsapp.ultramsg.token', 'token123');

        Http::fake([
            'https://api.ultramsg.com/*' => Http::response(['sent' => 'true'], 200)
        ]);

        $service = new WhatsAppService();
        $result = $service->send('+923000000000', 'Test Ultramsg Message');

        $this->assertTrue($result);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.ultramsg.com/instance123/messages/chat'
                && $request['to'] === '+923000000000'
                && $request['token'] === 'token123'
                && $request['body'] === 'Test Ultramsg Message';
        });
    }

    public function test_send_order_notification_to_multiple_recipients(): void
    {
        Config::set('services.whatsapp.driver', 'log');
        Config::set('services.whatsapp.to', '+923000000001, +923000000002');

        Log::shouldReceive('info')
            ->twice()
            ->withArgs(function ($message) {
                return str_contains($message, 'WhatsApp Notification (Driver: log) to +923000000001')
                    || str_contains($message, 'WhatsApp Notification (Driver: log) to +923000000002');
            });

        $order = new \App\Models\Order([
            'order_number' => 'ORD-12345',
            'customer_name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+923001234567',
            'shipping_address' => '123 Street',
            'city' => 'Lahore',
            'subtotal' => 100.00,
            'shipping_cost' => 10.00,
            'total' => 110.00,
            'payment_method' => 'cod',
        ]);
        $order->setRelation('items', collect([]));

        $service = new WhatsAppService();
        $result = $service->sendOrderNotification($order);

        $this->assertTrue($result);
    }

    public function test_send_via_callmebot_driver_success(): void
    {
        Config::set('services.whatsapp.driver', 'callmebot');
        Config::set('services.whatsapp.callmebot.apikey', 'apikey123');

        Http::fake([
            'https://api.callmebot.com/*' => Http::response(['status' => 'OK'], 200)
        ]);

        $service = new WhatsAppService();
        $result = $service->send('+923000000000', 'Test CallMeBot Message');

        $this->assertTrue($result);

        Http::assertSent(function ($request) {
            $url = parse_url($request->url());
            parse_str($url['query'] ?? '', $query);

            return str_starts_with($request->url(), 'https://api.callmebot.com/whatsapp.php')
                && ($query['phone'] ?? '') === '+923000000000'
                && ($query['text'] ?? '') === 'Test CallMeBot Message'
                && ($query['apikey'] ?? '') === 'apikey123';
        });
    }
}
