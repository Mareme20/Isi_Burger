<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture #{{ $commande->id }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 24px;
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #2f1a12;
            background: #fff;
        }
        .invoice { border: 1px solid #e7c9aa; border-radius: 8px; overflow: hidden; }
        .header { padding: 18px 20px; border-bottom: 1px solid #f0d7bd; background: #fff7ed; }
        .brand { font-size: 24px; font-weight: 800; letter-spacing: 1px; color: #bf4600; margin: 0; }
        .subtitle { margin: 3px 0 0; color: #7a5242; font-size: 11px; }
        .meta { width: 100%; margin-top: 14px; border-collapse: collapse; }
        .meta td { padding: 5px 0; vertical-align: top; }
        .section { padding: 16px 20px 8px; }
        .section-title { margin: 0 0 10px; font-size: 13px; text-transform: uppercase; letter-spacing: .4px; color: #6d4a3d; }
        .client-box { border: 1px solid #f1ddc7; border-radius: 6px; padding: 10px 12px; background: #fffdfa; }
        .client-name { font-size: 13px; font-weight: 700; margin: 0 0 4px; }
        .line-table { width: 100%; border-collapse: collapse; margin-top: 8px; font-size: 11.5px; }
        .line-table th { background: #f6e3cf; color: #5c3729; text-transform: uppercase; font-size: 10px; letter-spacing: .3px; text-align: left; padding: 8px; border: 1px solid #eed2b4; }
        .line-table td { padding: 8px; border: 1px solid #f0dcc5; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .summary { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .summary td { padding: 6px 8px; border: 1px solid #f0dcc5; }
        .summary .label { width: 75%; text-align: right; font-weight: 700; color: #6d4a3d; background: #fffdfa; }
        .summary .value { text-align: right; font-weight: 800; }
        .summary .total .label, .summary .total .value { background: #fff3e6; color: #bf4600; font-size: 13px; }
        .footer { padding: 14px 20px 18px; border-top: 1px dashed #eecfb2; margin-top: 10px; color: #7a5242; font-size: 10.5px; }
        .status { display: inline-block; padding: 4px 8px; border-radius: 999px; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; border: 1px solid #e5d3bf; background: #f9efe4; color: #734839; }
    </style>
</head>
<body>
    <div class="invoice">
        <div class="header">
            <h1 class="brand">ISI BURGER</h1>
            <p class="subtitle">Facture officielle de commande</p>
            <table class="meta">
                <tr>
                    <td>
                        <div><strong>Facture N°:</strong> CMD-{{ $commande->id }}</div>
                        <div><strong>Date:</strong> {{ $commande->created_at->format('d/m/Y H:i') }}</div>
                    </td>
                    <td class="text-right">
                        <span class="status">{{ str_replace('_', ' ', $commande->statut) }}</span>
                    </td>
                </tr>
            </table>
        </div>
        <div class="section">
            <h2 class="section-title">Client</h2>
            <div class="client-box">
                <p class="client-name">{{ $commande->user->name }}</p>
                <p style="margin:0;">{{ $commande->user->email }}</p>
            </div>
        </div>
        <div class="section">
            <h2 class="section-title">Lignes de commande</h2>
            <table class="line-table">
                <thead>
                    <tr>
                        <th>Désignation</th>
                        <th class="text-center">Quantité</th>
                        <th class="text-right">Prix Unitaire</th>
                        <th class="text-right">Sous-total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($commande->burgers as $burger)
                        <tr>
                            <td>{{ $burger->nom }}</td>
                            <td class="text-center">{{ $burger->pivot->quantite }}</td>
                            <td class="text-right">{{ number_format($burger->pivot->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                            <td class="text-right">{{ number_format($burger->pivot->prix_unitaire * $burger->pivot->quantite, 0, ',', ' ') }} FCFA</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <table class="summary">
                <tr>
                    <td class="label">Total commande</td>
                    <td class="value">{{ number_format($commande->total, 0, ',', ' ') }} FCFA</td>
                </tr>
                <tr class="total">
                    <td class="label">Montant à payer</td>
                    <td class="value">{{ number_format($commande->total, 0, ',', ' ') }} FCFA</td>
                </tr>
            </table>
        </div>
        <div class="footer">Merci d'avoir commandé chez ISI BURGER. Cette facture est générée automatiquement.</div>
    </div>
</body>
</html>
