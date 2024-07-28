@extends('layouts.admin-layout')

@section('title')
    - Product
@endsection

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Products</li>
        </ol>
        <h5 class="font-weight-bolder mb-0">Product</h5>
    </nav>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-1 p-3">
            <div class="card-header pb-3">
                <div class="row">
                    <div class="col d-flex align-items-center">
                        <h6>All Products</h6>
                    </div>
                    <div class="col">
                        <div class="d-flex justify-content-end flex-wrap">
                            <div class="mb-2" style="margin-right: 20px">
                                @if (!$categories->isEmpty())
                                    <select class="form-control" onchange="update(this.value)" id="categorySelect">
                                        
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                            @can('productAdd')
                                <div>
                                    @if ($categories->isEmpty())
                                        <a class="btn bg-gradient-dark mb-0" href="{{ route('category.create') }}"><i class="fas fa-plus"></i>&nbsp;&nbsp;Add Category</a>
                                    @else
                                        <a class="btn bg-gradient-dark mb-0" href="{{ route('product.create') }}"><i class="fas fa-plus"></i>&nbsp;&nbsp;Add Product</a>
                                    @endif
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table" id="dataTable3">
                        <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-1">Nama</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-1">Kategori</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-1">Stock</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-1">Modal</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-1">Harga</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-1">Rate</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-1">Action</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>

    function submit(key) {
        $('#form_'+key).submit();
    }

    $(document).ready(function () {
        var table = $('#dataTable3').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            ajax: {
                url: "{{ route('admin.dataTable.getProduct') }}",
                data: function (d) {
                    d.category_id = $('#categorySelect').val(); // Mengirim category_id ke server
                },
                error: function(xhr, error, thrown){
                    // console.log('An error occurred while fetching data.');
                        // Hide the default error message
                        $('#example').DataTable().clear().draw();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'category', name: 'category' },
                { data: 'jumlah', name: 'jumlah' },
                { data: 'modal', name: 'modal' },
                { data: 'harga', name: 'harga' },
                { data: 'rate', name: 'rate' },
                { data: 'action', name: 'action' }
            ],
            headerCallback: function(thead, data, start, end, display) {
                $(thead).find('th').css('text-align', 'left'); // pastikan align header tetap di tengah
            },
        });

        // Fungsi update untuk memperbarui tabel berdasarkan kategori yang dipilih
        window.update = function (category_id) {
            $('#dataTable3').DataTable().ajax.reload();
        }
    });

    function modalHapus(id) {
        Swal.fire({
            title: "Kamu yakin?",
            text: "Kamu tidak akan bisa membatalkannya setelah ini!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#a1a1a1",
            confirmButtonText: "Ya, hapus saja!"
        }).then((result) => {
            if (result.isConfirmed) {
                submit(id);
            }
        });
    }
</script>
@endsection