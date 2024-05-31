@extends('layouts.admin-layout')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Cashier</li>
        </ol>
        <h5 class="font-weight-bolder mb-0">Cashier</h5>
    </nav>
@stop

@section('content')

<div class="row mt-4">
    <div class="col-lg-8 mb-lg-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group has-validation">
                                <label for="kategori" class="form-control-label">{{ __('Menu') }}</label>
                                <div class="@error('menu')border border-danger rounded-3 @enderror">
                                    <select name="menu" id="menuSelect" class="form-control">
                                        <option value="" selected disabled>Pilih Menu</option>
                                        @foreach ($data as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('menu')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user-name" class="form-control-label">{{ __('Jumlah') }}</label>
                                <div class="@error('jumlah')border border-danger rounded-3 @enderror">
                                    <input class="form-control" type="number" min="1" placeholder="Jumlah" name="jumlah" id="jumlah" value="1">
                                    @error('jumlah')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="form-control-label">{{ __('Harga') }}</label>
                                <div class="@error('harga')border border-danger rounded-3 @enderror">
                                    <input class="form-control" type="number" placeholder="harga" id="harga" name="harga" value="0" disabled>
                                    @error('harga')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="form-control-label">{{ __('Stock') }}</label>
                                <div class="@error('stock')border border-danger rounded-3 @enderror">
                                    <input class="form-control" type="number" placeholder="Stock" name="stock" id="stock" disabled>
                                    @error('stock')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="form-control-label">{{ __('Total') }}</label>
                                <div class="@error('total')border border-danger rounded-3 @enderror">
                                    <input class="form-control" type="number" placeholder="Total" name="total" id="total" value="0" disabled>
                                    @error('total')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6" id="diskon">
                        
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn bg-gradient-dark btn-md mt-4">{{ 'Tambah ke cart' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card p-3">
            <div class="row mb-2">
                <div class="col">
                    <h5>Cart</h5>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    Nasi Goreng x2
                </div>
                <div class="col">
                    <div class="d-flex justify-content-end flex-wrap">
                        Rp. 40000
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    Disc (20%)
                </div>
                <div class="col">
                    <div class="d-flex justify-content-end flex-wrap">
                        (Rp. 2000)
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    Milo x1
                </div>
                <div class="col">
                    <div class="d-flex justify-content-end flex-wrap">
                        Rp. 18000
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col">
                    <p class="font-italic">Anda hemat:</p> 
                </div>
                <div class="col">
                    <div class="d-flex justify-content-end flex-wrap">
                        Rp. 2000
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <p class="font-italic">Total:</p> 
                </div>
                <div class="col">
                    <div class="d-flex justify-content-end flex-wrap">
                        Rp. 50000
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn bg-gradient-dark btn-md mt-4">{{ 'Submit' }}</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#menuSelect').change(function() {
            var productId = $(this).val();
            if (productId) {
                $.ajax({
                    url: '/get-detail/' + productId,
                    type: 'GET',
                    success: function(data) {
                        let jumlah = $('#jumlah').val();
                        $('#harga').val(data.harga);
                        $('#stock').val(data.stock);
                        $('#total').val(data.harga*jumlah);
                        if(data.diskon.length != 0) {
                            $('#diskonFill').remove();


                            let code = ''

                            data.diskon.forEach((item) => {
                                code+=`<option value="${ item.id }">${ item.name } - ${ item.percent }%</option>`
                            });

                            $('#diskon').append(`<div class="form-group has-validation" id="diskonFill">
                                <label for="diskon" class="form-control-label">{{ __('Diskon') }}</label>
                                <div class="@error('diskon')border border-danger rounded-3 @enderror">
                                    <select name="diskon" id="diskon" class="form-control">
                                        <option value="" selected disabled>Tanpa Diskon</option>
                                        ${ code }
                                    </select>
                                    @error('diskon')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>`)
                        } else $('#diskonFill').remove();
                    }
                });
            } else {
                $('#harga').val('');
            }
        });

        $('#jumlah').change(function() {
            var jumlah = $('#jumlah').val();
            var harga = $('#harga').val();
            if(jumlah <= 0) $('#jumlah').val(1);

            $('#total').val(jumlah*harga);
        });
    });
</script>
@endsection