@extends('layouts.master')


@section('top')
    <style>
        /* Container style */
        .container {
            max-width: 1200px; /* Adjust the maximum width as needed */
            margin: 0 auto; /* Center the container */
            padding: 20px; /* Add some padding for better readability */
        }

        /* Row style */
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: -10px; /* Adjust margin to create space between kolumns */
        }

        /* kolumn style */
        .kol {
            box-sizing: border-box;
            flex: 1;
            padding: 10px; /* Adjust padding to create space between kolumns */
        }

        /* Example styling for specific kolumn widths */
        .kol-1 { flex-basis: calc(8.333% - 20px); }
        .kol-2 { flex-basis: calc(16.667% - 20px); }
        .kol-3 { flex-basis: calc(25% - 20px); }
        .kol-4 { flex-basis: calc(33.333% - 20px); }
        .kol-5 { flex-basis: calc(41.667% - 20px); }
        .kol-6 { flex-basis: calc(50% - 20px); }
        .kol-7 { flex-basis: calc(58.333% - 20px); }
        .kol-8 { flex-basis: calc(66.667% - 20px); }
        .kol-9 { flex-basis: calc(75% - 20px); }
        .kol-10 { flex-basis: calc(83.333% - 20px); }
        .kol-11 { flex-basis: calc(91.667% - 20px); }
        .kol-12 { flex-basis: calc(100% - 20px); }

        /* Responsive styles (adjust as needed) */
        @media (max-width: 768px) {
            .kol {
                flex-basis: calc(100% - 20px); /* Full width on smaller screens */
            }
        }

        .bg-white {
            background: #fff;
        }

        .p-1 {
            padding: 1rem;
        }

        .p-2 {
            padding: 2rem;
        }

        .p-3 {
            padding: 3rem;
        }

        .p-4 {
            padding: 4rem;
        }

        .p-5 {
            padding: 5rem;
        }

        .shadow-sm {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            background-kolor: #ffffff;
            border-radius: 8px;
            }
    </style>
@endsection

@section('content')
<?php 
$mysqli = new mysqli("localhost", "root", "", "inventori");
if($mysqli->connect_error) {
  exit('Error connecting to database');
}
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli->set_charset("utf8mb4");    
?>
    <div>

        <div class="box-header">
            <h3 class="box-title">Profil {{ \Auth::user()->name  }}</h3>
        </div>

        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <div class="kol-12">
                    <div class="row">
                        <div class="kol-4 p-2">
                            <div class="bg-white shadow-sm p-2">
                                <div class="form-group">
                                    <img src="https://localhost/user.png" style="width: 100%">
                                </div>
                            </div>
                        </div>
                        <div class="kol-8 p-2">
                            <div class="bg-white shadow-sm p-2">
                                <div class="form-group">
                                    <label>Nama Lengkap</label>
                                    <input type="text" disabled value="{{ \Auth::user()->name }}" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Alamat Email</label>
                                    <input type="text" disabled value="{{ \Auth::user()->email }}" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Role</label>
                                    <input type="text" disabled value="{{ \Auth::user()->role }}" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid" style="padding-right:70px;">
            <div class="card bg-white p-0">
                <div class="card-body" style="padding-left: 14px;padding-right:14px;padding-top:14px;padding-bottom:0px;">
            <h4 style="font-weight: bold;">Histori Barang Keluar Bulan <?php echo date('F');?></h4>
                    <div id="charts" class="p-0"></div>
                </div>
            </div>
        </div>

        <br>
        <div class="container-fluid" style="padding-right:70px;">
            <div class="card bg-white p-0">
                <div class="card-body" style="padding-left: 14px;padding-right:14px;padding-top:14px;padding-bottom:0px;">
            <h4 style="font-weight: bold;">Histori Barang Masuk Bulan <?php echo date('F');?></h4>
                    <div id="chartsv2" class="p-0"></div>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>


    
<br>

<script src="https://wafarifki.com/dashboard/assets/compiled/js/app.js"></script>
<script src="https://wafarifki.com/dashboard/assets/extensions/apexcharts/apexcharts.min.js"></script>
<?php 
$mysqli = new mysqli("localhost", "root", "", "inventori");
if($mysqli->connect_error) {
  exit('Error connecting to database');
}
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli->set_charset("utf8mb4");    
?>
<script>
    <?php
    $currentMonth = date('n'); // Get the current month as a number
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, date('Y')); // Get the number of days in the current month

    $dates = [];
    for ($i = 1; $i <= $daysInMonth; $i++) {
        $date = date('j', strtotime(date("Y-m-$i"))); // Include both the day of the week and the day of the month
        $dates[] = "Tgl $date";
    }

    $data = [];
    for ($i = 1; $i <= $daysInMonth; $i++) {
        $date = date('j', strtotime(date("Y-m-$i"))); // Include both the day of the week and the day of the month
        $data[] = "data$date";
    }
    ?>
    var xaxisCategories = <?php echo json_encode($dates); ?>;
    
    <?php 
            
    for ($i = 1; $i <= $daysInMonth; $i++) {
        $date = date('j', strtotime(date("Y-m-$i"))); // Include both the day of the week and the day of the month
        $datev2 = date('d-m-Y', strtotime(date("Y-m-$i"))); // Include both the day of the week and the day of the month
        $dates[] = "Tgl $date";
        
        $waktu = date("Y-m-$i");
        
        $stmt = $mysqli->prepare("SELECT COUNT(`id`) AS `total` FROM product_keluar WHERE DATE(tanggal) = '$waktu'");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $resultdate = $row['total'];
        
        echo "var data$date = parseInt($resultdate);";
    }
    ?>
    
    
    <?php
    $jsData = '[' . implode(', ', $data) . ']';
    echo "var data = $jsData;";
    ?>
    
    var optionsProfileVisitv2 = {
        annotations: {
        position: "back",
        },
        dataLabels: {
        enabled: false,
        },
        chart: {
        type: "bar",
        height: 300,
        },
        fill: {
        opacity: 1,
        },
        plotOptions: {},
        series: [
        {
            name: "Total Visitor Per Hari",
            data: data,
        },
        ],
        colors: "#00c964",
        xaxis: {
            categories: xaxisCategories,
        },
    }
    
    var chartProfileVisitv2 = new ApexCharts(
        document.querySelector("#charts"),
        optionsProfileVisitv2
    )
    
    chartProfileVisitv2.render()
</script>

<script>
    <?php
    $datav2 = [];
    for ($i = 1; $i <= $daysInMonth; $i++) {
        $date = date('j', strtotime(date("Y-m-$i"))); // Include both the day of the week and the day of the month
        $datav2[] = "data_v_$date";
    }?>
    var xaxisCategoriesv2 = <?php echo json_encode($dates); ?>;
    
    <?php 
            
    for ($i = 1; $i <= $daysInMonth; $i++) {
        $date = date('j', strtotime(date("Y-m-$i"))); // Include both the day of the week and the day of the month
        $datev2 = date('d-m-Y', strtotime(date("Y-m-$i"))); // Include both the day of the week and the day of the month
        $dates[] = "Tgl $date";
        
        $waktu = date("Y-m-$i");
        
        $stmt = $mysqli->prepare("SELECT COUNT(`id`) AS `totals` FROM product_masuk WHERE DATE(tanggal) = '$waktu'");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $resultdate = $row['totals'];
        
        echo "var data_v_$date = parseInt($resultdate);";
    }
    ?>
    
    
    <?php
    $jsData = '[' . implode(', ', $datav2) . ']';
    echo "var datav2 = $jsData;";
    ?>
    
    var optionsProfileVisitv2 = {
        annotations: {
        position: "back",
        },
        dataLabels: {
        enabled: false,
        },
        chart: {
        type: "bar",
        height: 300,
        },
        fill: {
        opacity: 1,
        },
        plotOptions: {},
        series: [
        {
            name: "Total Visitor Per Hari",
            data: datav2,
        },
        ],
        colors: "#00a65a",
        xaxis: {
            categories: xaxisCategoriesv2,
        },
    }
    
    var chartProfileVisitv2_2 = new ApexCharts(
        document.querySelector("#chartsv2"),
        optionsProfileVisitv2
    )
    
    chartProfileVisitv2_2.render()
</script>
@endsection

@section('bot')
    
@endsection