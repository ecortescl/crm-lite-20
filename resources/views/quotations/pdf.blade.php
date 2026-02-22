<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Cotización {{ $quotation->quotation_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #1f2937;
            padding: 20px;
        }
        .header {
            width: 100%;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #111827;
        }
        .header-table {
            width: 100%;
        }
        .header-left {
            width: 55%;
            vertical-align: top;
        }
        .header-right {
            width: 45%;
            vertical-align: top;
            text-align: right;
        }
        .company-logo {
            max-height: 60px;
            max-width: 180px;
            margin-bottom: 10px;
        }
        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 6px;
        }
        .company-details {
            font-size: 9px;
            color: #374151;
            line-height: 1.6;
        }
        .company-rut {
            font-weight: bold;
            color: #111827;
        }
        .quotation-title {
            font-size: 28px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }
        .quotation-number {
            font-size: 14px;
            font-weight: bold;
            color: #374151;
            margin-bottom: 8px;
        }
        .date-box {
            display: inline-block;
            background: #f3f4f6;
            padding: 8px 12px;
            margin-top: 6px;
        }
        .date-row {
            font-size: 9px;
            margin-bottom: 2px;
        }
        .date-label {
            color: #6b7280;
            font-weight: bold;
        }
        .date-value {
            color: #111827;
            font-weight: bold;
        }
        .client-section {
            background: #f9fafb;
            padding: 12px;
            margin-bottom: 20px;
            border-left: 4px solid #111827;
        }
        .section-title {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            color: #6b7280;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        .client-name {
            font-size: 14px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 6px;
        }
        .client-info {
            font-size: 9px;
        }
        .client-label {
            font-weight: bold;
            color: #374151;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        thead {
            background-color: #111827;
            color: #fff;
        }
        th {
            padding: 8px 10px;
            text-align: left;
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        th.text-center {
            text-align: center;
        }
        th.text-right {
            text-align: right;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }
        td.text-center {
            text-align: center;
        }
        td.text-right {
            text-align: right;
        }
        .item-description {
            color: #111827;
            white-space: pre-line;
        }
        .totals-wrapper {
            width: 100%;
            margin-bottom: 20px;
        }
        .totals {
            width: 300px;
            float: right;
            border: 1px solid #e5e7eb;
        }
        .totals-body {
            padding: 12px;
            background: #f9fafb;
        }
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 3px 0;
            border: 0;
        }
        .totals-label {
            color: #374151;
            font-weight: bold;
        }
        .totals-value {
            font-weight: bold;
            color: #111827;
            text-align: right;
            white-space: nowrap;
        }
        .totals-footer {
            background: #111827;
            color: #fff;
            padding: 12px;
        }
        .totals-footer .totals-label {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            color: #fff;
        }
        .totals-footer .totals-value {
            font-size: 18px;
            font-weight: bold;
            color: #fff;
            text-align: right;
        }
        .notes-section {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid #e5e7eb;
        }
        .notes-table {
            width: 100%;
        }
        .notes-cell {
            width: 50%;
            padding: 0 8px;
            vertical-align: top;
        }
        .notes-box {
            padding: 10px;
            border-left: 4px solid;
        }
        .notes-box.important {
            background: #eff6ff;
            border-color: #3b82f6;
        }
        .notes-box.terms {
            background: #fffbeb;
            border-color: #f59e0b;
        }
        .notes-title {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        .notes-box.important .notes-title {
            color: #1e40af;
        }
        .notes-box.terms .notes-title {
            color: #b45309;
        }
        .notes-content {
            white-space: pre-line;
            color: #374151;
            font-size: 9px;
            line-height: 1.6;
        }
        .footer {
            margin-top: 25px;
            padding-top: 12px;
            border-top: 1px solid #e5e7eb;
        }
        .footer-table {
            width: 100%;
        }
        .footer-left {
            width: 50%;
            vertical-align: top;
        }
        .footer-right {
            width: 50%;
            text-align: right;
            vertical-align: top;
        }
        .footer-text {
            font-size: 8px;
            color: #6b7280;
            line-height: 1.6;
        }
        .footer-text strong {
            color: #374151;
        }
        .footer-text em {
            font-style: italic;
        }
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>
<body>
    <div class="header">
        <table class="header-table">
            <tr>
                <td class="header-left">
                    @if($companySettings['logo'])
                        <img src="{{ public_path('storage/' . $companySettings['logo']) }}" alt="Logo" class="company-logo">
                    @endif
                    <div class="company-name">{{ $companySettings['name'] ?? 'Empresa' }}</div>
                    <div class="company-details">
                        @if($companySettings['rut'])
                            <div class="company-rut">RUT: {{ $companySettings['rut'] }}</div>
                        @endif
                        @if($companySettings['giro'])
                            <div>{{ $companySettings['giro'] }}</div>
                        @endif
                        @if($companySettings['address'])
                            <div style="margin-top: 4px;">{{ $companySettings['address'] }}</div>
                        @endif
                        @if($companySettings['phone'])
                            <div>Tel: {{ $companySettings['phone'] }}</div>
                        @endif
                        @if($companySettings['email'])
                            <div>{{ $companySettings['email'] }}</div>
                        @endif
                    </div>
                </td>
                <td class="header-right">
                    <div class="quotation-title">COTIZACIÓN</div>
                    <div class="quotation-number">{{ $quotation->quotation_number }}</div>
                    <div class="date-box">
                        <div class="date-row"><span class="date-label">Fecha Emisión:</span> <span class="date-value">{{ $quotation->issue_date->format('d/m/Y') }}</span></div>
                        <div class="date-row"><span class="date-label">Válida Hasta:</span> <span class="date-value">{{ $quotation->valid_until->format('d/m/Y') }}</span></div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="client-section">
        <div class="section-title">Señor(es)</div>
        <div class="client-name">{{ $quotation->client_name }}</div>
        <div class="client-info">
            @if($quotation->client_rut)
                <div><span class="client-label">RUT:</span> {{ $quotation->client_rut }}</div>
            @endif
            @if($quotation->client_phone)
                <div><span class="client-label">Teléfono:</span> {{ $quotation->client_phone }}</div>
            @endif
            @if($quotation->client_email)
                <div><span class="client-label">Email:</span> {{ $quotation->client_email }}</div>
            @endif
            @if($quotation->client_address)
                <div><span class="client-label">Dirección:</span> {{ $quotation->client_address }}</div>
            @endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Descripción</th>
                <th class="text-center" style="width: 60px;">Cant.</th>
                <th class="text-right" style="width: 100px;">Precio Unit.</th>
                <th class="text-right" style="width: 100px;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quotation->items as $item)
                <tr>
                    <td class="item-description">{{ $item['description'] }}</td>
                    <td class="text-center">{{ number_format($item['quantity'], 2, ',', '.') }}</td>
                    <td class="text-right">${{ number_format($item['unit_price'], 0, ',', '.') }}</td>
                    <td class="text-right" style="font-weight: bold;">${{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals-wrapper clearfix">
        <div class="totals">
            <div class="totals-body">
                <table class="totals-table">
                    <tr>
                        <td class="totals-label">Subtotal:</td>
                        <td class="totals-value">${{ number_format($quotation->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="totals-label">IVA ({{ $quotation->tax_rate }}%):</td>
                        <td class="totals-value">${{ number_format($quotation->tax_amount, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
            <div class="totals-footer">
                <table class="totals-table">
                    <tr>
                        <td class="totals-label">Total:</td>
                        <td class="totals-value">${{ number_format($quotation->total, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    @if($quotation->notes || $quotation->terms)
        <div class="notes-section clearfix">
            <table class="notes-table">
                <tr>
                    @if($quotation->notes)
                        <td class="notes-cell">
                            <div class="notes-box important">
                                <div class="notes-title">Notas Importantes</div>
                                <div class="notes-content">{{ $quotation->notes }}</div>
                            </div>
                        </td>
                    @endif
                    @if($quotation->terms)
                        <td class="notes-cell">
                            <div class="notes-box terms">
                                <div class="notes-title">Términos y Condiciones</div>
                                <div class="notes-content">{{ $quotation->terms }}</div>
                            </div>
                        </td>
                    @endif
                </tr>
            </table>
        </div>
    @endif

    <div class="footer">
        <table class="footer-table">
            <tr>
                <td class="footer-left">
                    <div class="footer-text">
                        <strong>Emitida por:</strong> {{ $quotation->user->name }}<br>
                        <strong>Fecha de emisión:</strong> {{ $quotation->created_at->format('d/m/Y H:i') }}
                    </div>
                </td>
                <td class="footer-right">
                    <div class="footer-text">
                        <em>Esta cotización fue generada electrónicamente</em><br>
                        <em>y tiene validez sin firma ni timbre</em>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
