@extends('layouts.master')


@section('top')
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap4.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css"/>
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
    <div class="box">

        <div class="box-header">
            <h3 class="box-title">History Transaksi</h3>
        </div>

        <!-- /.box-header -->
        <div class="box-body">
            <div class="table-responsive">
                <table id="datatable" class="table">
                    <thead>
                        <tr>
                            <th>Customer / Supplier</th>
                            <th class="text-center">Waktu Transaksi</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-center">Tipe</th>
                            <th class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $stmt_produkmasuk = $mysqli->prepare("SELECT *, product_masuk.qty, SUM(product_masuk.qty) AS total_quantity FROM product_masuk LEFT JOIN products ON products.id = product_masuk.product_id LEFT JOIN suppliers ON suppliers.id = product_masuk.supplier_id GROUP BY suppliers.nama, product_masuk.tanggal");
                        $stmt_produkmasuk->execute();
                        $result_produkmasuk = $stmt_produkmasuk->get_result();
                        $nomor = 1;
                        while($row_produkmasuk = $result_produkmasuk->fetch_assoc()) {?>
                            <tr>
                                <td style="font-weight: bold;">
                                    <?php echo $row_produkmasuk['nama'];?>
                                </td>
                                <td class="text-center">
                                    <?php echo $row_produkmasuk['tanggal'];?>
                                </td>
                                <td class="text-center">
                                    <?php echo $row_produkmasuk['total_quantity'];?>
                                </td>
                                <td class="text-center">
                                    <span>Produk Masuk</span>
                                </td>
                                <td class="text-center">
                                    <div style="display: flex;">
                                        <form action="{{ url()->current() }}" method="post">
                                            @csrf
                                            <input type="hidden" value="<?php echo $row_produkmasuk['tanggal'];?>" name="tanggal" autocomplete="off" />
                                            <input type="hidden" value="<?php echo $row_produkmasuk['supplier_id'];?>" name="supplier" />   
                                            <input type="hidden" value="produkmasukexcel" name="tipe" />
                                            <button type="submit" name="downloadinvoice" class="btn btn-sm btn-success w-100">Export Data Excel</button>
                                        </form>
                                        <form action="{{ url()->current() }}" method="post">
                                            @csrf
                                            <input type="hidden" value="<?php echo $row_produkmasuk['tanggal'];?>" name="tanggal" autocomplete="off" />
                                            <input type="hidden" value="<?php echo $row_produkmasuk['supplier_id'];?>" name="supplier" />   
                                            <input type="hidden" value="produkmasukpdf" name="tipe" />
                                            <button type="submit" name="downloadinvoice" class="btn btn-sm btn-danger w-100">Export Data PDF</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php }?>

                        <?php 
                        $stmt_produkkeluar = $mysqli->prepare("SELECT *, product_keluar.qty, SUM(product_keluar.qty) AS total_quantity FROM product_keluar LEFT JOIN products ON products.id = product_keluar.product_id LEFT JOIN customers ON customers.id = product_keluar.customer_id GROUP BY customers.namacustomer, product_keluar.tanggal");
                        $stmt_produkkeluar->execute();
                        $result_produkkeluar = $stmt_produkkeluar->get_result();
                        $nomors = 1;
                        while($row_produkkeluar = $result_produkkeluar->fetch_assoc()) {?>
                            <tr>
                                <td style="font-weight: bold;">
                                    <?php echo $row_produkkeluar['namacustomer'];?>
                                </td>
                                <td class="text-center">
                                    <?php echo $row_produkkeluar['tanggal'];?>
                                </td>
                                <td class="text-center">
                                    <?php echo $row_produkkeluar['total_quantity'];?>
                                </td>
                                <td class="text-center">
                                    <span>Produk Keluar</span>
                                </td>
                                <td class="text-center">
                                    <div style="display: flex;">
                                        <form action="{{ url()->current() }}" method="post">
                                            @csrf
                                            <input type="hidden" value="<?php echo $row_produkkeluar['tanggal'];?>" name="tanggal" autocomplete="off" />
                                            <input type="hidden" value="<?php echo $row_produkkeluar['customer_id'];?>" name="customer" />
                                            <input type="hidden" value="produkkeluarexcel" name="tipe" />
                                            <button type="submit" name="downloadinvoice" class="btn btn-sm btn-success w-100">Export Data Excel</button>
                                        </form>
                                        <form action="{{ url()->current() }}" method="post">
                                            @csrf
                                            <input type="hidden" value="<?php echo $row_produkkeluar['tanggal'];?>" name="tanggal" autocomplete="off" />
                                            <input type="hidden" value="<?php echo $row_produkkeluar['customer_id'];?>" name="customer" />   
                                            <input type="hidden" value="produkkeluarpdf" name="tipe" />
                                            <button type="submit" name="downloadinvoice" class="btn btn-sm btn-danger w-100">Export Data PDF</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php }?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.box-body -->
    </div>

@endsection

@section('bot')
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.5.6/js/buttons.bootstrap4.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="//cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.5.6/js/buttons.colVis.min.js"></script>
    
    <script>
        $(document).ready(function() {
          var table = $('#datatable').DataTable({
            "initComplete": function() {
              $("#myTable").show();
            }
          });
          
        });
        
        $(function () {
            $("#upload").change(function () {
                var input = this;
                var url = $(this).val();
                var ext = url.substring(url.lastIndexOf(".") + 1).toLowerCase();
                if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
                    var reader = new FileReader();
        
                    reader.onload = function (e) {
                        $("#img").attr("src", e.target.result);
                    };
                    reader.readAsDataURL(input.files[0]);
                } else {
                    $("#img").attr("src", "../uploads/12.jpg");
                }
            });
        });

    </script>
@endsection
