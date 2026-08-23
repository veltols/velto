<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send a general WhatsApp message.
     *
     * @param string $to
     * @param string $message
     * @return bool
     */
    public function send(string $to, string $message): bool
    {
        $driver = config('services.whatsapp.driver', 'log');

        if (empty($to)) {
            Log::warning('WhatsApp service: No target phone number (to) set.');
            return false;
        }

        switch ($driver) {
            case 'twilio':
                return $this->sendViaTwilio($to, $message);
            case 'ultramsg':
                return $this->sendViaUltramsg($to, $message);
            case 'callmebot':
                return $this->sendViaCallMeBot($to, $message);
            case 'log':
            default:
                Log::info("WhatsApp Notification (Driver: {$driver}) to {$to}:\n{$message}");
                return true;
        }
    }

    /**
     * Send a notification for a newly placed order.
     *
     * @param Order $order
     * @return bool
     */
    public function sendOrderNotification(Order $order): bool
    {
        $toConfig = config('services.whatsapp.to');
        if (!$toConfig) {
            Log::warning('WhatsApp order notification: WHATSAPP_TO number not configured.');
            return false;
        }

        $recipients = array_filter(array_map('trim', explode(',', $toConfig)));
        if (empty($recipients)) {
            Log::warning('WhatsApp order notification: No valid recipient phone numbers found in WHATSAPP_TO.');
            return false;
        }

        $order->load('items');

        $message = "🔔 *New Order Placed!*\n\n";
        $message .= "*Order Number:* " . $order->order_number . "\n";
        $message .= "*Customer:* " . $order->customer_name . " (" . $order->phone . ")\n";
        $message .= "*Email:* " . $order->email . "\n";
        $message .= "*Shipping Address:* " . $order->shipping_address . ", " . $order->city . "\n\n";
        
        $message .= "*Items:*\n";
        foreach ($order->items as $item) {
            $variant = $item->variant_info ? " ({$item->variant_info})" : "";
            $message .= "- " . $item->product_name . $variant . " x " . $item->quantity . " - Rs. " . number_format($item->price, 2) . "\n";
        }
        $message .= "\n";
        
        $message .= "*Subtotal:* Rs. " . number_format($order->subtotal, 2) . "\n";
        $message .= "*Shipping Cost:* Rs. " . number_format($order->shipping_cost, 2) . "\n";
        $message .= "*Total:* Rs. " . number_format($order->total, 2) . "\n";
        $message .= "*Payment Method:* " . strtoupper($order->payment_method) . "\n";

        $allSuccess = true;
        foreach ($recipients as $recipient) {
            $result = $this->send($recipient, $message);
            if (!$result) {
                $allSuccess = false;
            }
        }

        return $allSuccess;
    }

    /**
     * Send message using Twilio WhatsApp API.
     *
     * @param string $to
     * @param string $message
     * @return bool
     */
    protected function sendViaTwilio(string $to, string $message): bool
    {
        $sid = config('services.whatsapp.twilio.sid');
        $token = config('services.whatsapp.twilio.token');
        $from = config('services.whatsapp.twilio.from');

        if (!$sid || !$token || !$from) {
            Log::error('WhatsApp service: Twilio credentials missing in configuration.');
            return false;
        }

        // Ensure recipient number is formatted properly for Twilio WhatsApp (whatsapp:+1234567890)
        $formattedTo = str_starts_with($to, 'whatsapp:') ? $to : 'whatsapp:' . $to;
        $formattedFrom = str_starts_with($from, 'whatsapp:') ? $from : 'whatsapp:' . $from;

        try {
            $response = Http::withBasicAuth($sid, $token)
                ->asForm()
                ->post("https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json", [
                    'To' => $formattedTo,
                    'From' => $formattedFrom,
                    'Body' => $message,
                ]);

            if ($response->successful()) {
                return true;
            }

            Log::error('WhatsApp service (Twilio) failed: ' . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error('WhatsApp service (Twilio) exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send message using Ultramsg API.
     *
     * @param string $to
     * @param string $message
     * @return bool
     */
    protected function sendViaUltramsg(string $to, string $message): bool
    {
        $instanceId = config('services.whatsapp.ultramsg.instance_id');
        $token = config('services.whatsapp.ultramsg.token');

        if (!$instanceId || !$token) {
            Log::error('WhatsApp service: Ultramsg credentials missing in configuration.');
            return false;
        }

        // Ultramsg phone numbers just require the country code and number, no 'whatsapp:' prefix
        $formattedTo = str_replace('whatsapp:', '', $to);

        try {
            $response = Http::asForm()
                ->post("https://api.ultramsg.com/{$instanceId}/messages/chat", [
                    'token' => $token,
                    'to' => $formattedTo,
                    'body' => $message,
                ]);

            if ($response->successful() && isset($response['sent']) && $response['sent'] === 'true') {
                return true;
            }

            Log::error('WhatsApp service (Ultramsg) failed: ' . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error('WhatsApp service (Ultramsg) exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send message using CallMeBot API.
     *
     * @param string $to
     * @param string $message
     * @return bool
     */
    protected function sendViaCallMeBot(string $to, string $message): bool
    {
        $apikey = config('services.whatsapp.callmebot.apikey');

        if (!$apikey) {
            Log::error('WhatsApp service: CallMeBot API key missing in configuration.');
            return false;
        }

        try {
            $response = Http::get("https://api.callmebot.com/whatsapp.php", [
                'phone' => $to,
                'text' => $message,
                'apikey' => $apikey,
            ]);

            if ($response->successful()) {
                return true;
            }

            Log::error('WhatsApp service (CallMeBot) failed: ' . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error('WhatsApp service (CallMeBot) exception: ' . $e->getMessage());
            return false;
        }
    }
}
