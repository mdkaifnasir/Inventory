<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print QR Codes -
        <?php echo $college->name; ?>
    </title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
        @media print {
            .no-print {
                display: none;
            }

            body {
                margin: 0;
                padding: 0;
            }
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f7f6;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .qr-card {
            background: white;
            padding: 15px;
            border: 1px solid #eee;
            border-radius: 12px;
            text-align: center;
            page-break-inside: avoid;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .qr-container {
            margin-bottom: 10px;
            padding: 10px;
            background: #fff;
            border: 1px solid #f0f0f0;
            border-radius: 8px;
        }

        .asset-name {
            font-weight: 800;
            font-size: 12px;
            color: #333;
            margin: 5px 0;
            text-transform: uppercase;
            width: 100%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .asset-tag {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            font-weight: bold;
            color: #0277bd;
            background: #e1f5fe;
            padding: 2px 8px;
            border-radius: 4px;
        }

        .btn-print {
            background: #000;
            color: white;
            padding: 10px 25px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: bold;
            font-size: 14px;
            transition: opacity 0.2s;
        }

        .btn-print:hover {
            opacity: 0.8;
        }
    </style>
</head>

<body>

    <div class="header no-print">
        <h1 style="margin:0; font-size: 24px;">QR Label SheetGenerator</h1>
        <p style="color: #666; margin: 5px 0 15px;">Ready to print QR codes for <strong>
                <?php echo $college->name; ?>
            </strong></p>
        <button onclick="window.print()" class="btn-print">Print All Labels</button>
    </div>

    <div class="grid">
        <?php foreach ($assets as $asset): ?>
            <div class="qr-card">
                <div class="qr-container" id="qr-<?php echo $asset->id; ?>"></div>
                <div class="asset-name">
                    <?php echo $asset->name; ?>
                </div>
                <div class="asset-tag">
                    <?php echo $asset->asset_tag; ?>
                </div>
                <script>
                    new QRCode(document.getElementById("qr-<?php echo $asset->id; ?>"), {
                        text: "<?php echo site_url('assets/view_details/' . $asset->asset_tag); ?>",
                        width: 120,
                        height: 120,
                        colorDark: "#000000",
                        colorLight: "#ffffff",
                        correctLevel: QRCode.CorrectLevel.M
                    });
                </script>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (empty($assets)): ?>
        <div style="text-align: center; padding: 100px; color: #999;">
            <h2>No assets assigned to this institution.</h2>
        </div>
    <?php endif; ?>

</body>

</html>