<!DOCTYPE html>
<html>
<head>
    <title>History Produk Masuk</title>

    <!-- Add inline styles to set margins to 0 -->
    <style>
        @page {
            margin: 20px !important;
            padding: 20px !important;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
            vertical-align: middle;
        }

        th {
            vertical-align: middle;
            background-color: #007bff; /* Blue color for the header */
            color: #fff; /* White text for the header */
        }

        .tabel {
        width: 100%;
        border-collapse: collapse;
        }

        .th, .td {
        border: 1px solid #fff;
        padding: 8px;
        text-align: left;
        background: #fff;
        color: #000;
        }

        /* Style for every row */
        .tr {
        background-color: #fff;
        }

        /* Style for every fourth column (assuming 1-based index) */
        .td:nth-child(3) {
        background-color: #fff;
        }
    </style>
</head>
<body>
<br><br>
    <div class="containers">
        <table class="tabel">
            <thead>
                <tr class="tr">
                    <th class="th" style="font-weight: bold">Penerima</th>
                    <th class="th" style="font-weight: bold">Pengirim</th>
                    <th class="th" style="font-weight: bold">Informasi Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                <tr class="tr">
                    <td class="td">
                        <span>{{ $results[0]->supplier_name }}</span><br>
                        <span>{{ $results[0]->email }}</span><br>
                        <span>{{ $results[0]->telepon }}</span><br>
                        <span>{{ $results[0]->alamat }}</span>
                    </td>
                    <td class="td">
                        <span>Inventy</span><br>
                        <span>info@inventy@gmail.com</span><br>
                        <span>08950212211</span><br>
                        <span>Kota Bekasi, 17215 Indonesia</span>
                    </td>
                    <td class="td">
                        <span>Bank Central Asia</span><br>
                        <span>074-1832-124</span><br>
                        <span>A.n PT. Inventy</span><br>
                    </td>
                </tr>
            <!-- Add more rows as needed -->
            </tbody>
        </table>
        <br>
        <div style='width: 630px;height:1px;background:#222;margin-left: 40px;' class='ms-auto me-auto mt-4'></div>
    </div>

    <br>
    
    <div class="container pt-4 px-0">
        <table width="100%" class="mt-1 table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Supplier</th>
                    <th class="text-center">Nama Produk</th>
                    <th class="text-center">Waktu</th>
                    <th class="text-center">Harga</th>
                    <th class="text-center">Jumlah</th>
                    <th class="text-center">Sub Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($results as $index => $result)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">{{ $result->supplier_name }}</td>
                        <td class="text-center">{{ $result->product_name }}</td>
                        <td class="text-center">{{ $result->tanggal }}</td>
                        <td class="text-center">Rp {{ number_format($result->hargaproduk) }}</td>
                        <td class="text-center">{{ $result->qty }}</td>
                        <td class="text-center">Rp {{ number_format($result->qty * $result->hargaproduk) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-end"><strong>Total</strong></td>
                    <td class="text-center"><strong>{{ $totalQty }}</strong></td>
                    <td class="text-center"><strong>Rp {{ number_format($totalhargatampil) }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="container w-100 pt-4 px-0">
        <div style="width:100%;display:block;">
            <div style="text-align:end;float: right;">
                <h6 style="padding-right: 50px;">{{ $waktu }}, Bekasi</h6>
                <br>
                <img src="http://localhost/public/images/ttd.png" alt="Signature Image" style="margin-left:20px;width: 80px;">
                <br>
                <span style="margin-left:16px;">Ardi Christian</span>
            </div>
        </div>
    </div>
</body>
</html>