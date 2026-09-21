<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - Velto Leather Shoes</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f4f5;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #18181b;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }
        .header {
            background-color: #000000;
            padding: 30px 40px;
            text-align: center;
        }
        .header img {
            max-height: 48px;
            width: auto;
        }
        .header-title {
            color: #ffffff;
            font-size: 13px;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            margin-top: 12px;
            font-weight: 600;
        }
        .hero {
            padding: 40px 40px 20px 40px;
            text-align: center;
        }
        .badge {
            display: inline-block;
            background-color: #ecfdf5;
            color: #059669;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 6px 14px;
            border-radius: 9999px;
            margin-bottom: 16px;
        }
        .title {
            font-size: 24px;
            font-weight: 800;
            color: #09090b;
            margin: 0 0 10px 0;
            letter-spacing: -0.02em;
        }
        .subtitle {
            font-size: 14px;
            color: #71717a;
            line-height: 1.5;
            margin: 0;
        }
        .order-meta {
            background-color: #fafafa;
            border-top: 1px solid #f4f4f5;
            border-bottom: 1px solid #f4f4f5;
            padding: 20px 40px;
            margin-top: 25px;
        }
        .meta-grid {
            display: table;
            width: 100%;
        }
        .meta-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .meta-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #a1a1aa;
            margin-bottom: 4px;
        }
        .meta-value {
            font-size: 14px;
            font-weight: 700;
            color: #18181b;
        }
        .section {
            padding: 30px 40px;
        }
        .section-heading {
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #09090b;
            margin: 0 0 20px 0;
            padding-bottom: 8px;
            border-bottom: 1px solid #e4e4e7;
        }
        .item-row {
            display: table;
            width: 100%;
            padding: 14px 0;
            border-bottom: 1px solid #f4f4f5;
        }
        .item-img {
            display: table-cell;
            width: 60px;
            vertical-align: middle;
        }
        .item-img img {
            width: 56px;
            height: 56px;
            object-fit: contain;
            background-color: #fafafa;
            border: 1px solid #f4f4f5;
            border-radius: 4px;
        }
        .item-info {
            display: table-cell;
            vertical-align: middle;
            padding-left: 16px;
        }
        .item-name {
            font-size: 14px;
            font-weight: 700;
            color: #18181b;
            margin: 0 0 4px 0;
        }
        .item-variant {
            font-size: 12px;
            color: #71717a;
            margin: 0;
        }
        .item-price {
            display: table-cell;
            width: 110px;
            text-align: right;
            vertical-align: middle;
            font-size: 14px;
            font-weight: 700;
            color: #18181b;
        }
        .item-qty {
            font-size: 11px;
            color: #a1a1aa;
            font-weight: 500;
        }
        .summary-table {
            width: 100%;
            margin-top: 20px;
        }
        .summary-table td {
            padding: 6px 0;
            font-size: 13px;
            color: #71717a;
        }
        .summary-table td.amount {
            text-align: right;
            color: #18181b;
            font-weight: 600;
        }
        .summary-table tr.total-row td {
            padding-top: 14px;
            border-top: 2px solid #18181b;
            font-size: 16px;
            font-weight: 800;
            color: #09090b;
        }
        .summary-table tr.total-row td.amount {
            font-size: 18px;
            color: #09090b;
        }
        .address-box {
            background-color: #fafafa;
            border-radius: 6px;
            padding: 20px;
            margin-top: 10px;
            font-size: 13px;
            line-height: 1.6;
            color: #52525b;
        }
        .address-box strong {
            color: #18181b;
        }
        .footer {
            background-color: #18181b;
            color: #a1a1aa;
            padding: 30px 40px;
            text-align: center;
            font-size: 12px;
            line-height: 1.6;
        }
        .footer a {
            color: #ffffff;
            text-decoration: none;
        }
        .btn-whatsapp {
            display: inline-block;
            background-color: #25D366;
            color: #ffffff !important;
            padding: 12px 24px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 700;
            font-size: 13px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        
        <!-- Header -->
        <div class="header">
            <a href="{{ config('app.url', url('/')) }}" target="_blank" style="text-decoration: none;">
                <img src="{{ url('images/footerlogo.png') }}" alt="Velto Leather Shoes" style="max-height: 44px; width: auto; display: block; margin: 0 auto;">
            </a>
            <div class="header-title">Velto Leather Shoes</div>
        </div>

        <!-- Hero Section -->
        <div class="hero">
            <div class="badge">✓ Order Confirmed</div>
            <h1 class="title">Thank You for Your Order!</h1>
            <p class="subtitle">
                Hi {{ $order->customer_name }}, we have received your order. We are preparing your handcrafted leather footwear for dispatch.
            </p>
        </div>

        <!-- Order Meta Details -->
        <div class="order-meta">
            <div class="meta-grid">
                <div class="meta-col">
                    <div class="meta-label">Order Number</div>
                    <div class="meta-value">{{ $order->order_number }}</div>
                </div>
                <div class="meta-col" style="text-align: right;">
                    <div class="meta-label">Payment Method</div>
                    <div class="meta-value">Cash on Delivery (COD)</div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="section">
            <h2 class="section-heading">Order Summary</h2>

            @foreach($order->items as $item)
                @php
                    $imgUrl = null;
                    if ($item->product && $item->product->primaryImage && !empty($item->product->primaryImage->image_path)) {
                        $path = $item->product->primaryImage->image_path;
                        $imgUrl = str_starts_with($path, 'http') ? $path : url('storage/' . $path);
                    } elseif ($item->product && $item->product->images && $item->product->images->isNotEmpty()) {
                        $path = $item->product->images->first()->image_path;
                        $imgUrl = str_starts_with($path, 'http') ? $path : url('storage/' . $path);
                    } else {
                        $imgUrl = url('images/hero-shoes.png');
                    }
                @endphp
                <div class="item-row">
                    <div class="item-img">
                        <img src="{{ $imgUrl }}" alt="{{ $item->product_name }}" width="56" height="56" style="width:56px;height:56px;object-fit:contain;">
                    </div>
                    <div class="item-info">
                        <div class="item-name">{{ $item->product_name }}</div>
                        @if($item->variant_info)
                            <p class="item-variant">Variant: {{ $item->variant_info }}</p>
                        @endif
                        <span class="item-qty">Qty: {{ $item->quantity }}</span>
                    </div>
                    <div class="item-price">
                        PKR {{ number_format($item->subtotal) }}
                    </div>
                </div>
            @endforeach

            <!-- Cost Summary -->
            <table class="summary-table">
                <tr>
                    <td>Subtotal</td>
                    <td class="amount">PKR {{ number_format($order->subtotal) }}</td>
                </tr>
                <tr>
                    <td>Delivery Charges</td>
                    <td class="amount">
                        @if($order->shipping_cost > 0)
                            PKR {{ number_format($order->shipping_cost) }}
                        @else
                            FREE
                        @endif
                    </td>
                </tr>
                <tr class="total-row">
                    <td>Total Amount Due</td>
                    <td class="amount">PKR {{ number_format($order->total) }}</td>
                </tr>
            </table>
        </div>

        <!-- Delivery Address Details -->
        <div class="section" style="padding-top: 0;">
            <h2 class="section-heading">Delivery Information</h2>
            <div class="address-box">
                <strong>{{ $order->customer_name }}</strong><br>
                {{ $order->shipping_address }}<br>
                {{ $order->city }}@if($order->postal_code), {{ $order->postal_code }}@endif<br>
                Phone: <strong>{{ $order->phone }}</strong><br>
                Email: {{ $order->email }}
            </div>
            
            <div style="text-align: center; margin-top: 25px;">
                <p style="font-size: 13px; color: #71717a; margin: 0;">Need any changes or quick assistance regarding your order?</p>
                <a href="https://wa.me/923069101633?text=Hello%20Velto!%20I%20have%20an%20inquiry%20about%20my%20order%20{{ $order->order_number }}" class="btn-whatsapp" target="_blank">
                    Chat on WhatsApp
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 0 0 10px 0; font-weight: 700; color: #ffffff; letter-spacing: 0.1em; text-transform: uppercase;">
                Velto Leather Shoes
            </p>
            <p style="margin: 0 0 10px 0;">
                Abid Market Qainchi Main Ferozpur Road, Lahore<br>
                Call: 0306 9101633 | Email: veltoleathershoes@gmail.com
            </p>
            <p style="margin: 15px 0 0 0; font-size: 11px; color: #71717a;">
                &copy; {{ date('Y') }} Velto Leather Shoes. All rights reserved.
            </p>
        </div>

    </div>
</body>
</html>
