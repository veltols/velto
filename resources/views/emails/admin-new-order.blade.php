<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order Notification - Admin</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f4f5;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #18181b;
        }
        .container {
            max-width: 600px;
            margin: 25px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }
        .header {
            background-color: #09090b;
            padding: 24px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .header h1 {
            color: #ffffff;
            font-size: 16px;
            font-weight: 800;
            margin: 0;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        .header .badge {
            background-color: #ef4444;
            color: #ffffff;
            font-size: 11px;
            font-weight: 800;
            padding: 4px 10px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .hero {
            padding: 30px 32px 10px 32px;
        }
        .order-title {
            font-size: 22px;
            font-weight: 800;
            margin: 0 0 6px 0;
            color: #09090b;
        }
        .order-sub {
            font-size: 13px;
            color: #71717a;
            margin: 0;
        }
        .alert-box {
            background-color: #fef2f2;
            border-left: 4px solid #ef4444;
            padding: 14px 16px;
            margin: 20px 32px;
            font-size: 13px;
            color: #991b1b;
            line-height: 1.5;
            border-radius: 2px;
        }
        .customer-card {
            background-color: #fafafa;
            border: 1px solid #e4e4e7;
            border-radius: 6px;
            padding: 18px 22px;
            margin: 0 32px 24px 32px;
        }
        .customer-card h3 {
            margin: 0 0 12px 0;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #71717a;
        }
        .card-row {
            display: table;
            width: 100%;
            padding: 4px 0;
            font-size: 13px;
        }
        .card-label {
            display: table-cell;
            width: 120px;
            color: #71717a;
            font-weight: 600;
        }
        .card-val {
            display: table-cell;
            color: #09090b;
            font-weight: 700;
        }
        .section {
            padding: 0 32px 24px 32px;
        }
        .table-items {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            margin-top: 10px;
        }
        .table-items th {
            text-align: left;
            padding: 10px 0;
            border-bottom: 2px solid #e4e4e7;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #71717a;
        }
        .table-items td {
            padding: 12px 0;
            border-bottom: 1px solid #f4f4f5;
            vertical-align: middle;
        }
        .table-items td.num {
            text-align: right;
            font-weight: 700;
            color: #09090b;
        }
        .summary-total {
            margin-top: 16px;
            background-color: #09090b;
            color: #ffffff;
            border-radius: 6px;
            padding: 16px 20px;
            display: table;
            width: 100%;
            box-sizing: border-box;
        }
        .summary-total .lbl {
            display: table-cell;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            vertical-align: middle;
        }
        .summary-total .val {
            display: table-cell;
            font-size: 20px;
            font-weight: 800;
            text-align: right;
            vertical-align: middle;
        }
        .action-btns {
            padding: 24px 32px 32px 32px;
            text-align: center;
        }
        .btn {
            display: inline-block;
            padding: 12px 22px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            text-decoration: none;
            margin: 0 6px 8px 6px;
        }
        .btn-black {
            background-color: #09090b;
            color: #ffffff !important;
        }
        .btn-whatsapp {
            background-color: #25D366;
            color: #ffffff !important;
        }
        .btn-call {
            background-color: #2563eb;
            color: #ffffff !important;
        }
        .footer {
            background-color: #f4f4f5;
            padding: 16px;
            text-align: center;
            font-size: 11px;
            color: #a1a1aa;
            border-top: 1px solid #e4e4e7;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <table width="100%" bgcolor="#09090b" cellpadding="20" cellspacing="0">
            <tr>
                <td style="color:#ffffff; font-size:15px; font-weight:800; letter-spacing:0.1em; text-transform:uppercase;">
                    Velto Store Admin Alert
                </td>
                <td align="right">
                    <span style="background-color:#ef4444; color:#ffffff; font-size:11px; font-weight:800; padding:4px 10px; border-radius:3px; text-transform:uppercase;">
                        New Order
                    </span>
                </td>
            </tr>
        </table>

        <!-- Hero -->
        <div class="hero">
            <h2 class="order-title">Order #{{ $order->order_number }}</h2>
            <p class="order-sub">Placed on {{ $order->created_at ? $order->created_at->format('M d, Y \a\t h:i A') : date('M d, Y \a\t h:i A') }}</p>
        </div>

        <!-- Alert Notification -->
        <div class="alert-box">
            ⚡ <strong>Action Required:</strong> A new order has been placed. Please review customer details and confirm the order for dispatch.
        </div>

        <!-- Customer Card -->
        <div class="customer-card">
            <h3>Customer & Shipping Information</h3>
            <div class="card-row">
                <div class="card-label">Customer Name:</div>
                <div class="card-val">{{ $order->customer_name }}</div>
            </div>
            <div class="card-row">
                <div class="card-label">Phone Number:</div>
                <div class="card-val">
                    <a href="tel:{{ $order->phone }}" style="color:#09090b; text-decoration:none; font-weight:800;">
                        {{ $order->phone }}
                    </a>
                </div>
            </div>
            <div class="card-row">
                <div class="card-label">Email:</div>
                <div class="card-val">{{ $order->email }}</div>
            </div>
            <div class="card-row">
                <div class="card-label">Delivery City:</div>
                <div class="card-val">{{ $order->city }}</div>
            </div>
            <div class="card-row">
                <div class="card-label">Complete Address:</div>
                <div class="card-val">{{ $order->shipping_address }}</div>
            </div>
            <div class="card-row">
                <div class="card-label">Payment:</div>
                <div class="card-val" style="color:#16a34a;">Cash on Delivery (COD)</div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="section">
            <h3 style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.08em; color: #71717a; margin: 0 0 10px 0;">Ordered Items</h3>
            <table class="table-items">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Variant</th>
                        <th style="text-align:center;">Qty</th>
                        <th style="text-align:right;">Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->product_name }}</strong>
                            </td>
                            <td style="color:#71717a; font-size:12px;">
                                {{ $item->variant_info ?? 'Standard' }}
                            </td>
                            <td align="center">
                                x{{ $item->quantity }}
                            </td>
                            <td class="num">
                                PKR {{ number_format($item->subtotal) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Total Bar -->
            <table width="100%" bgcolor="#09090b" cellpadding="14" cellspacing="0" style="border-radius:4px; margin-top:16px;">
                <tr>
                    <td style="color:#a1a1aa; font-size:13px; font-weight:700; text-transform:uppercase;">
                        Total Payable (Inc. Shipping)
                    </td>
                    <td align="right" style="color:#ffffff; font-size:18px; font-weight:800;">
                        PKR {{ number_format($order->total) }}
                    </td>
                </tr>
            </table>
        </div>

        <!-- Direct Admin Actions -->
        <div class="action-btns">
            <!-- Open Admin Order Panel -->
            @if(Route::has('admin.orders.show'))
                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-black" target="_blank">
                    Open in Admin Panel
                </a>
            @endif

            <!-- WhatsApp Customer Directly -->
            @php
                $cleanPhone = preg_replace('/[^0-9]/', '', $order->phone);
                if (str_starts_with($cleanPhone, '03')) {
                    $cleanPhone = '92' . substr($cleanPhone, 1);
                }
            @endphp
            <a href="https://wa.me/{{ $cleanPhone }}?text=Hello%20{{ urlencode($order->customer_name) }},%20thank%20you%20for%20your%20order%20{{ $order->order_number }}%20at%20Velto%20Leather%20Shoes.%20We%20are%20confirming%20your%20order." class="btn btn-whatsapp" target="_blank">
                WhatsApp Customer
            </a>

            <!-- Call Customer -->
            <a href="tel:{{ $order->phone }}" class="btn btn-call">
                Call Customer
            </a>
        </div>

        <!-- Footer -->
        <div class="footer">
            Velto Leather Shoes Automated Notification System &bull; {{ config('app.url') }}
        </div>
    </div>
</body>
</html>
