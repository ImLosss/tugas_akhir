<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Receipt example</title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="{{ asset('./assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('./assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="{{ asset('./assets/js/plugins/fontawesome.js') }}" crossorigin="anonymous"></script>
    <link href="{{ asset('./assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- CSS Files -->

    {{-- jQuery --}}
    <script src="{{ asset('assets/js/jquery-1.11.1.min.js') }}"
    crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            font-size: 10px;
            font-family: 'Times New Roman';
        }
        .table-responsive {
            margin-top: 10px;
        }
        .report-date {
            text-align: right;
        }

        
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col text-center">
                <h2>Laporan Kopingumpul</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-12 mt-3">
                <table class="table table-borderless">
                    <tr>
                        <td width="120px"><b>Total pemasukan</b></td>
                        <td width="20px"><b>:</b></td>
                        <td><b>Rp{{ number_format($total) }}</b></td>
                    </tr>
                    <tr>
                        <td><b>Total keuntungan</b></td>
                        <td><b>:</b></td>
                        <td><b>Rp{{ number_format($profit) }}</b></td>
                        <td>
                            <div class="report-date">
                                <b>{{ $strDate }}</b>
                            </div>
                        </td>
                    </tr>
                    
                </table>
            </div>
            
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Menu</th>
                        <th>diskon</th>
                        <th>Total</th>
                        <th>Profit</th>
                        <th>Waktu pesan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order as $item)
                        <tr>
                            <td colspan="6" class="text-center">{{ $item->created_at }} / kasir: {{ $item->kasir }} @if ($item->bazar == true) / partner @endif </td>
                        </tr>
                        @foreach ($item->carts as $no => $cart)
                            <tr>
                                <td>{{ $no+1 }}</td>
                                <td>{{ $cart->menu }}</td>
                                <td>{{ $cart->total_diskon ? 'Rp' . number_format($cart->total_diskon) : 'none'  }}</td>
                                <td>Rp{{ number_format($cart->total) }}</td>
                                <td>Rp{{ number_format($cart->profit) }}</td>
                                <td>{{ $cart->created_at }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                    <!-- Tambahkan baris lain sesuai kebutuhan -->
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-11">
                <div class="d-flex justify-content-end">
                    <div class="text-left">
                        Makassar, {{ $assignDate }}<br>Owner,<br><br><br><div style="text-decoration: underline">{{ $signatory }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('components.admin-script')
    <script>
        $(document).ready( function () {
            // Pemanggilan window.print() akan melakukan pencetakan saat halaman dimuat
            window.onload = function() {
                window.print();
            }

            // Deteksi ketika pencetakan dibatalkan (dalam beberapa kasus)
            window.onafterprint = function() {
                // Kembali ke halaman sebelumnya jika pencetakan dibatalkan
                window.location.href = document.referrer;
            }
        });
    </script>
</body>
</html>
