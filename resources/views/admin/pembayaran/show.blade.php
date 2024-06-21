@extends('layouts.admin-layout')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" @role('admin')href="{{ route('home') }}"@endrole>Home</a></li>
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ route('payment') }}">Payments</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Show</li>
        </ol>
        <h5 class="font-weight-bolder mb-0">List Pembayaran</h5>
    </nav>
@endsection
@section('content')
<style>
    .custom-checkbox {
        /* Tambahkan gaya kustom sesuai kebutuhan */
        width: 20px;
        height: 20px;
        cursor: pointer;
    }
    
</style>
<div class="row">
    <div class="col-12">
        <div class="card mb-1 p-3">
            <div class="card-header pb-3">
                <div class="row">
                    <div class="col-6 d-flex align-items-center">
                        <h6>Meja {{ $order->no_meja }}</h6>
                    </div>
                    <div class="col-6 text-end">
                        <button class="btn bg-gradient-success mb-1" onclick="modalUpdateStatusAll()" id="btnUpdatePayment" disabled>Selesaikan pembayaran</button>
                        <button class="btn bg-gradient-dark mb-1" id="btnCetakNota" disabled>Cetak nota</button>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <form action="{{ route('payment.billOrUpdate') }}" method="POST" enctype="multipart/form-data" id="formCetakNota">
                        @csrf
                        <input type="text" name="action" id="action" hidden>
                        <input type="text" name="payment" id="payment" hidden>
                        <input type="text" name="updateMeja" id="updateMeja" hidden>
                        <input type="text" name="no_meja" value="{{ $order->no_meja }}" hidden>
                        <table class="table" id="dataTable3">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        <div class="form-check">
                                            <input class="form-check-input custom-checkbox" type="checkbox" value="" id="selectPesanAll">
                                        </div>
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Menu</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jumlah</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status pembayaran</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    function modalUpdateStatusAll() {
        Swal.fire({
            title: 'Metode pembayaran',
            html:
                '<select class="form-control" id="paymentAlert"><option>Tunai</option><option>Non Tunai</option></select><br>' +
                '<p class="text-danger text-xs mt-2">Anda tidak akan dapat menghapus pesanan ini setelah pembayaran Lunas</p>' +
                '<label for="updateMeja">' +
                '<input type="checkbox" id="updateMejaAlert">&nbsp;Update status meja jadi tidak terpakai?</label>',
            focusConfirm: false,
            showCancelButton: true,
            preConfirm: () => {
                let payment = $('#paymentAlert').val();
                let updateTable = false;
                if ($('#updateMejaAlert').prop('checked')) {
                    updateTable = true;
                }

                $('#action').val('updatePayment');
                $('#payment').val(payment);
                $('#updateMeja').val(updateTable);

                submit_form();
            }
        });
    }

    function updateStatus(key) {
        $('#formUpdate_'+key).submit();
    }

    function submit_form() {
        $('#formCetakNota').submit();
    }

    $('#btnCetakNota').on('click', function() {
        $('#action').val('printBill');
        submit_form()
    });

    $(document).ready( function () {

        // Handle select all checkbox
        $('#selectPesanAll').on('click', function() {
            $('input[name="selectPesan[]"]').prop('checked', this.checked);
            checkSelected();
        });

        // Handle individual checkbox to update select all checkbox state
        $(document).on('click', 'input[name="selectPesan[]"]', function() {
            if ($('input[name="selectPesan[]"]:checked').length == $('input[name="selectPesan[]"]').length) {
                $('#selectPesanAll').prop('checked', true);
            } else {
                $('#selectPesanAll').prop('checked', false);
            }
            checkSelected()
        });

        function checkSelected() {
            if ($('input[name="selectPesan[]"]:checked').length > 0) {
                // Jika ada, hilangkan attribute disabled dari button
                $('#btnCetakNota').removeAttr('disabled');
                $('#btnUpdatePayment').removeAttr('disabled');
            } else {
                // Jika tidak ada, tambahkan attribute disabled ke button
                $('#btnCetakNota').attr('disabled', 'disabled');
                $('#btnUpdatePayment').attr('disabled', 'disabled');
            }
        }

        var table = $('#dataTable3').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ordering: false,
            // "columnDefs": [
            //     { "orderable": false, "targets": 0 }  // Menonaktifkan pengurutan pada kolom pertama (index 0)
            // ],
            ajax: {
                url: "{{ route('admin.dataTable.getPayment', $order->no_meja) }}"
            },
            columns: [
                {
                    data: '#',
                    name: '#'
                },
                {
                    data: 'menu',
                    name: 'menu'
                },
                {
                    data: 'jumlah',
                    name: 'jumlah'
                },
                {
                    data: 'total',
                    name: 'total'
                },
                {
                    data: 'status_pembayaran',
                    name: 'status_pembayaran'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ],
            language: {
                emptyTable: "Tidak menemukan pesanan yang belum Lunas",
                loadingRecords: "Memuat..."
            },
            columnDefs: [
                { width: '40px', targets: 0 }
            ],
        });
    } );

    function modalHapus(id) {
        Swal.fire({
            title: "Kamu yakin?",
            text: "Kamu tidak akan bisa membatalkannya setelah ini!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, hapus saja!"
        }).then((result) => {
            if (result.isConfirmed) {
                submit(id);
            }
        });
    }

    function modalUpdateStatus(id) {
        // Swal.fire({
        //     title: "Kamu yakin?",
        //     text: "Kamu tidak akan bisa membatalkannya setelah ini!",
        //     icon: "warning",
        //     showCancelButton: true,
        //     confirmButtonColor: "#3085d6",
        //     cancelButtonColor: "#d33",
        //     confirmButtonText: "Selesaikan pembayaran!"
        // }).then((result) => {
        //     if (result.isConfirmed) {
        //         updateStatus(id);
        //     }
        // });

        Swal.fire({
            title: 'Metode pembayaran',
            html:
                '<select class="form-control" id="paymentAlert"><option>Tunai</option><option>Non Tunai</option></select><br>' +
                '<p class="text-danger text-xs mt-2">Anda tidak akan dapat menghapus pesanan ini setelah pembayaran Lunas</p>' +
                '<label for="updateMeja">' +
                '<input type="checkbox" id="updateMejaAlert">&nbsp;Update status meja jadi tidak terpakai?</label>',
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: "Selesaikan pembayaran!",
            confirmButtonColor: "#3085d6",  
            preConfirm: () => {
                let payment = $('#paymentAlert').val();
                let updateTable = false;
                if ($('#updateMejaAlert').prop('checked')) {
                    updateTable = true;
                }

                let code = `<input name="paymentSingle" value="${ payment }" hidden> <input name="updateMejaSingle" value="${ updateTable }" hidden> <input name="no_meja_single" value="{{ $order->no_meja }}" hidden>`;
                $('#formUpdate_'+id).append(code);

                updateStatus(id);
            }
        });
    }

    

    // function modalUpdateStatusAll() {
    //     Swal.fire({
    //         title: "Kamu yakin?",
    //         text: "Kamu tidak akan bisa membatalkannya setelah ini!",
    //         icon: "warning",
    //         showCancelButton: true,
    //         confirmButtonColor: "#3085d6",
    //         cancelButtonColor: "#d33",
    //         confirmButtonText: "Selesaikan pembayaran!"
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             $('#action').val('updatePayment');
    //             submit_form();
    //         }
    //     });
    // }
</script>
@endsection