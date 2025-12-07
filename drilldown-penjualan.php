<?php
// --- 1. KONEKSI DATABASE ---
$host = "localhost";
$user = "stonebon_uhighcharts"; // Sesuaikan user db
$pass = "Bismillah123";     // Sesuaikan password db
$db   = "stonebon_highcharts"; // Sesuaikan nama db

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// --- 2. LOGIKA PHP UNTUK MENYUSUN DATA JSON ---

$data_series = [];      // Untuk Level 1 (Utama)
$data_drilldown = [];   // Untuk Level 2 & 3

// A. Ambil Data Level 1 (Kategori)
$sql_kat = "SELECT kategori, SUM(jumlah) as total FROM penjualan GROUP BY kategori";
$res_kat = $conn->query($sql_kat);

while($row_kat = $res_kat->fetch_assoc()) {
    $nama_kat = $row_kat['kategori'];
    
    // Masukkan ke Series Utama
    $data_series[] = [
        'name' => $nama_kat,
        'y'    => (float)$row_kat['total'],
        'drilldown' => $nama_kat // ID untuk link ke level 2
    ];

    // B. Ambil Data Level 2 (Sub Kategori) berdasarkan Kategori ini
    $sql_sub = "SELECT sub_kategori, SUM(jumlah) as total 
                FROM penjualan 
                WHERE kategori = '$nama_kat' 
                GROUP BY sub_kategori";
    $res_sub = $conn->query($sql_sub);
    
    $sub_data = []; // Penampung data level 2
    
    while($row_sub = $res_sub->fetch_assoc()) {
        $nama_sub = $row_sub['sub_kategori'];
        // ID Unik untuk Level 3 (gabungan kat + sub)
        $drilldown_id_level3 = $nama_kat . '-' . $nama_sub; 

        $sub_data[] = [
            'name' => $nama_sub,
            'y' => (float)$row_sub['total'],
            'drilldown' => $drilldown_id_level3 // ID link ke level 3
        ];

        // C. Ambil Data Level 3 (Produk) berdasarkan Sub Kategori ini
        $sql_prod = "SELECT produk, SUM(jumlah) as total 
                     FROM penjualan 
                     WHERE kategori = '$nama_kat' AND sub_kategori = '$nama_sub' 
                     GROUP BY produk";
        $res_prod = $conn->query($sql_prod);
        
        $prod_data = []; // Penampung data level 3
        while($row_prod = $res_prod->fetch_assoc()) {
            $prod_data[] = [
                $row_prod['produk'], 
                (float)$row_prod['total']
            ];
        }

        // Push data Level 3 ke array Drilldown utama
        $data_drilldown[] = [
            'id' => $drilldown_id_level3,
            'name' => 'Produk di ' . $nama_sub,
            'data' => $prod_data
        ];
    }

    // Push data Level 2 ke array Drilldown utama
    $data_drilldown[] = [
        'id' => $nama_kat,
        'name' => 'Sub Kategori ' . $nama_kat,
        'data' => $sub_data
    ];
}

// Konversi ke JSON
$json_series = json_encode($data_series);
$json_drilldown = json_encode($data_drilldown);

?>

<!DOCTYPE html>
<html lang="id">
    <!--
<head>
    <meta charset="UTF-8">
    <title>Highcharts Drilldown 3 Level</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        #container { min-width: 310px; max-width: 800px; height: 400px; margin: 0 auto; }
    </style>
</head> -->

<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Highcharts Example</title>

		<style type="text/css">
* {
    font-family:
        -apple-system,
        BlinkMacSystemFont,
        "Segoe UI",
        Roboto,
        Helvetica,
        Arial,
        "Apple Color Emoji",
        "Segoe UI Emoji",
        "Segoe UI Symbol",
        sans-serif;
}

.highcharts-figure,
.highcharts-data-table table {
    min-width: 310px;
    max-width: 800px;
    margin: 1em auto;
}

#container {
    height: 400px;
}

.highcharts-data-table table {
    font-family: Verdana, sans-serif;
    border-collapse: collapse;
    border: 1px solid var(--highcharts-neutral-color-10, #e6e6e6);
    margin: 10px auto;
    text-align: center;
    width: 100%;
    max-width: 500px;
}

.highcharts-data-table caption {
    padding: 1em 0;
    font-size: 1.2em;
    color: var(--highcharts-neutral-color-60, #666);
}

.highcharts-data-table th {
    font-weight: 600;
    padding: 0.5em;
}

.highcharts-data-table td,
.highcharts-data-table th,
.highcharts-data-table caption {
    padding: 0.5em;
}

.highcharts-data-table thead tr,
.highcharts-data-table tbody tr:nth-child(even) {
    background: var(--highcharts-neutral-color-3, #f7f7f7);
}

.highcharts-description {
    margin: 0.3rem 10px;
}

@media (prefers-color-scheme: dark) {
    body {
        background-color: #141414;
        color: #ddd;
    }
    a {
        color: #2caffe;
    }
}
		</style>
	</head>

<body>

    <h2 style="text-align:center">Grafik Penjualan 3 Level (MySQL)</h2>
    <div id="container"></div>

    <!-- Load Library Highcharts -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/drilldown.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
	<script src="https://code.highcharts.com/modules/export-data.js"></script>
	<script src="https://code.highcharts.com/modules/accessibility.js"></script>
	<script src="https://code.highcharts.com/themes/adaptive.js"></script>

    <script type="text/javascript">
        // Ambil data JSON dari PHP
        const seriesData = <?php echo $json_series; ?>;
        const drilldownData = <?php echo $json_drilldown; ?>;

        Highcharts.chart('container', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Data Penjualan (Klik bar untuk detail)'
            },
            subtitle: {
                text: 'Level: Kategori > Sub Kategori > Produk'
            },
            xAxis: {
                type: 'category'
            },
            yAxis: {
                title: {
                    text: 'Total Penjualan'
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{point.y}'
                    }
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b><br/>'
            },
            // DATA UTAMA (LEVEL 1)
            series: [{
                name: 'Kategori',
                colorByPoint: true,
                data: seriesData
            }],
            // DATA DRILLDOWN (LEVEL 2 & 3)
            drilldown: {
                series: drilldownData
            }
        });
    </script>
</body>
</html>