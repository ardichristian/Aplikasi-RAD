<?php

namespace App\Http\Controllers;


use App\Exports\ExportSales;
use App\Imports\SalesImport;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Sale;
use Excel;
use PDF;
use Illuminate\Support\Facades\DB;



class SaleController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin,staff');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('sales.index');
    }

    public function downloadhistoryprodukkeluarmasuk(Request $request) {
        $tipe = $request->input('tipe');

        if($tipe == "produkmasukexcel"){
            try {
            // Assuming you have a "sales" table in your database

                $results = DB::table('product_masuk')
				->join('suppliers', 'product_masuk.supplier_id', '=', 'suppliers.id')
				->join('products', 'product_masuk.product_id', '=', 'products.id')
				->where('product_masuk.tanggal', $request->input('tanggal'))
				->where('product_masuk.supplier_id', $request->input('supplier'))
				->select('product_masuk.id as product_masuk_id', 'suppliers.nama as supplier_name', 'suppliers.email', 'suppliers.telepon', 'suppliers.alamat', 'products.namaproduk as product_name', 'products.harga as hargaproduk', 'product_masuk.qty', 'product_masuk.tanggal')
				->get();
				if ($results) {
					$totalQty = 0;
					$totalhargatampil = 0;
					$waktu = date('d F Y');
					$tanggalll = $request->input('tanggal');
					$waktuv2 = $tanggalll.date('Hi');
					header("Content-type: application/vnd-ms-excel");
					header("Content-Disposition: attachment; filename=$waktuv2-produkmasuk.xls");
					echo '<head><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"></head>';
					echo '<div class="container pt-5 px-5">
						<table width="100%" class="mt-1 table table-borderless">
							<thead>
								<tr class="tr">
									<th class="th" style="font-weight: bold">Penerima</th>
									<th class="th" style="font-weight: bold">Pengirim</th>
									<th class="th text-end" style="font-weight: bold">Informasi Pembayaran</th>
								</tr>
							</thead>
							<tbody>
								<tr class="tr">
									<td class="td">
										<span>'.$results[0]->supplier_name.'</span><br>
										<span>'.$results[0]->email.'</span><br>
										<span>'.$results[0]->telepon.'</span><br>
										<span>'.$results[0]->alamat.'</span>
									</td>
									<td class="td">
										<span>Inventy</span><br>
										<span>info@inventy@gmail.com</span><br>
										<span>08950212211</span><br>
										<span>Kota Bekasi, 17215 Indonesia</span>
									</td>
									<td class="td text-end">
										<span>Bank Central Asia</span><br>
										<span>074-1832-124</span><br>
										<span>A.n PT. Inventy</span><br>
									</td>
								</tr>
							<!-- Add more rows as needed -->
							</tbody>
						</table>
					</div>';
					echo "<div style='width: 930px;height:1px;background:#222;' class='ms-auto me-auto mt-4'></div>";
					echo "<br><br><div class='container pt-4 px-5'>
					<table width='100%' class='mt-1 table table-striped'>
						<thead>
							<tr>
								<th class='text-center'>No</th>
								<th class='text-center'>Supplier</th>
								<th class='text-center'>Nama Produk</th>
								<th class='text-center'>Waktu Transaksi</th>
								<th class='text-center'>Harga Satuan Produk</th>
								<th class='text-center'>Jumlah Transaksi</th>
								<th class='text-center'>Sub Harga</th>
							</tr>
						</thead>
						<tbody>";
						foreach ($results as $index => $result) {
							$number = $index + 1;
							$totalQty += $result->qty;
							$totalharga = $result->qty * $result->hargaproduk;
							$totalhargatampil += $totalharga;
							echo "<tr>
									<td class='text-center'>$number</td>
									<td class='text-center'>{$result->supplier_name}</td>
									<td class='text-center'>{$result->product_name}</td>
									<td class='text-center'>{$result->tanggal}</td>
									<td class='text-center'>'Rp ";
										echo number_format($result->hargaproduk);
							echo "</td>
									<td class='text-center'>{$result->qty}</td>
									<td class='text-center'>'Rp ";
									echo number_format($totalharga);
							echo "</td>
								</tr>";
						}
					echo "</tbody>
					<tfoot>
						<tr>
							<td colspan='5' class='text-end'><strong>Total</strong></td>
							<td class='text-center'><strong>$totalQty</strong></td>
							<td class='text-center'><strong>'Rp ".number_format($totalhargatampil)."</strong></td>
						</tr>
					</tfoot>
					</table>
					</div>";
					
					echo '<br><br><div class="container pt-4 px-5"><table class="w-100">
						<tbody>
						<!-- Add more rows as needed -->
						</tbody>
					</table></div><br><br>';
				} else {
					// Handle case when the record with the specified id is not found
					echo "Record not found.";
				}
            } catch (\Exception $e) {
                // Handle database connection or query error
                echo "Error: " . $e->getMessage();
            }
        } else if($tipe == "produkkeluarexcel") {
            try {
            // Assuming you have a "sales" table in your database
                $results = DB::table('product_keluar')
				->join('customers', 'product_keluar.customer_id', '=', 'customers.id')
				->join('products', 'product_keluar.product_id', '=', 'products.id')
				->where('product_keluar.tanggal', $request->input('tanggal'))
				->where('product_keluar.customer_id', $request->input('customer'))
				->select('product_keluar.id as product_keluar_id', 'customers.namacustomer as customers_name', 'customers.email', 'customers.alamat', 'customers.telepon', 'products.namaproduk as product_name', 'products.harga as hargaproduk', 'product_keluar.qty', 'product_keluar.tanggal')
				->get();
				if ($results) {
					$totalQty = 0;
					$totalhargatampil = 0;
					$waktu = date('d F Y');
					$tanggalll = $request->input('tanggal');
					$waktuv2 = $tanggalll.date('Hi');
					header("Content-type: application/vnd-ms-excel");
					header("Content-Disposition: attachment; filename=$waktuv2-produkkeluar.xls");
					echo '<head><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"></head>';
					echo '<div class="container pt-5 px-5">
						<table width="100%" class="mt-1 table table-borderless">
							<thead>
								<tr class="tr">
									<th class="th" style="font-weight: bold">Penerima</th>
									<th class="th" style="font-weight: bold">Pengirim</th>
									<th class="th text-end" style="font-weight: bold">Informasi Pembayaran</th>
								</tr>
							</thead>
							<tbody>
								<tr class="tr">
									<td class="td">
										<span>'.$results[0]->customers_name.'</span><br>
										<span>'.$results[0]->email.'</span><br>
										<span>'.$results[0]->telepon.'</span><br>
										<span>'.$results[0]->alamat.'</span>
									</td>
									<td class="td">
										<span>Inventy</span><br>
										<span>info@inventy@gmail.com</span><br>
										<span>08950212211</span><br>
										<span>Kota Bekasi, 17215 Indonesia</span>
									</td>
									<td class="td text-end">
										<span>Bank Central Asia</span><br>
										<span>074-1832-124</span><br>
										<span>A.n PT. Inventy</span><br>
									</td>
								</tr>
							<!-- Add more rows as needed -->
							</tbody>
						</table>
					</div>';
					echo "<div style='width: 930px;height:1px;background:#222;' class='ms-auto me-auto mt-4'></div>";
					echo "<br><br><div class='container pt-4 px-5'>
					<table width='100%' class='mt-1 table table-striped'>
						<thead>
							<tr>
								<th class='text-center'>No</th>
								<th class='text-center'>Supplier</th>
								<th class='text-center'>Nama Produk</th>
								<th class='text-center'>Waktu Transaksi</th>
								<th class='text-center'>Harga Satuan Produk</th>
								<th class='text-center'>Jumlah Transaksi</th>
								<th class='text-center'>Sub Harga</th>
							</tr>
						</thead>
						<tbody>";
						foreach ($results as $index => $result) {
							$number = $index + 1;
							$totalQty += $result->qty;
							$totalharga = $result->qty * $result->hargaproduk;
							$totalhargatampil += $totalharga;
							echo "<tr>
									<td class='text-center'>$number</td>
									<td class='text-center'>{$result->customers_name}</td>
									<td class='text-center'>{$result->product_name}</td>
									<td class='text-center'>{$result->tanggal}</td>
									<td class='text-center'>'Rp ";
										echo number_format($result->hargaproduk);
							echo "</td>
									<td class='text-center'>{$result->qty}</td>
									<td class='text-center'>'Rp ";
									echo number_format($totalharga);
							echo "</td>
								</tr>";
						}
					echo "</tbody>
					<tfoot>
						<tr>
							<td colspan='5' class='text-end'><strong>Total</strong></td>
							<td class='text-center'><strong>$totalQty</strong></td>
							<td class='text-center'><strong>'Rp ".number_format($totalhargatampil)."</strong></td>
						</tr>
					</tfoot>
					</table>
					</div>";

					echo '<br><br><div class="container pt-4 px-5"><table class="w-100">
						<tbody>
						<!-- Add more rows as needed -->
						</tbody>
					</table></div><br><br>';
				} else {
					// Handle case when the record with the specified id is not found
					echo "Record not found.";
				}
            } catch (\Exception $e) {
                // Handle database connection or query error
                echo "Error: " . $e->getMessage();
            }
        } else if($tipe == "produkmasukpdf") {
			$waktuv2 = $request->input('tanggal');
			try {
				// Assuming you have a "sales" table in your database
				$results = DB::table('product_masuk')
				->join('suppliers', 'product_masuk.supplier_id', '=', 'suppliers.id')
				->join('products', 'product_masuk.product_id', '=', 'products.id')
				->where('product_masuk.tanggal', $request->input('tanggal'))
				->where('product_masuk.supplier_id', $request->input('supplier'))
				->select('product_masuk.id as product_masuk_id', 'suppliers.nama as supplier_name', 'suppliers.email', 'suppliers.telepon', 'suppliers.alamat', 'products.namaproduk as product_name', 'products.harga as hargaproduk', 'product_masuk.qty', 'product_masuk.tanggal')
				->get();
				
				if ($results->count() > 0) {
					// Calculate total quantity
					$totalQty = $results->sum('qty');

					$waktu = date('d F Y');

					// Calculate total harga tampil
					$totalhargatampil = $results->sum(function ($result) {
						return $result->qty * $result->hargaproduk;
					});

					// Generate HTML content
					$html = view('sales.pdfviewv1', compact('results', 'totalQty', 'totalhargatampil', 'waktu'))->render();

					$pdf = PDF::loadHTML($html);

					// Return the PDF as a response
					return $pdf->stream("$waktuv2-produkmasuk.pdf");
				} else {
					// Handle case when no records are found
					return response('No records found.', 404);
				}
			} catch (\Exception $e) {
				// Handle database connection or query error
				return response('Error: ' . $e->getMessage(), 500);
			}
		} else if($tipe == "produkkeluarpdf") {
			$waktuv2 = $request->input('tanggal');
			try {
				// Assuming you have a "sales" table in your database
				$results = DB::table('product_keluar')
					->join('customers', 'product_keluar.customer_id', '=', 'customers.id')
					->join('products', 'product_keluar.product_id', '=', 'products.id')
					->where('product_keluar.tanggal', $request->input('tanggal'))
					->where('product_keluar.customer_id', $request->input('customer'))
					->select('product_keluar.id as product_keluar_id', 'customers.namacustomer as customers_name', 'customers.email', 'customers.alamat', 'customers.telepon', 'products.namaproduk as product_name', 'products.harga as hargaproduk', 'product_keluar.qty', 'product_keluar.tanggal')
					->get();

				if ($results->count() > 0) {
					// Calculate total quantity
					$totalQty = $results->sum('qty');

					$waktu = date('d F Y');

					// Calculate total harga tampil
					$totalhargatampil = $results->sum(function ($result) {
						return $result->qty * $result->hargaproduk;
					});

					// Generate HTML content
					$html = view('sales.pdfviewv2', compact('results', 'totalQty', 'totalhargatampil', 'waktu'))->render();

					$pdf = PDF::loadHTML($html);

					// Return the PDF as a response
					return $pdf->stream("$waktuv2-produkkeluar.pdf");
				} else {
					// Handle case when no records are found
					return response('No records found.', 404);
				}
			} catch (\Exception $e) {
				// Handle database connection or query error
				return response('Error: ' . $e->getMessage(), 500);
			}
		}
    }

}