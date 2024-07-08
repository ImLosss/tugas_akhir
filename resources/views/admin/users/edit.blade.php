@extends('layouts.admin-layout')

@section('title')
    - Edit User
@endsection

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ route('user') }}">Users</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Edit User</li>
        </ol>
        <h5 class="font-weight-bolder mb-0">User</h5>
    </nav>
@stop

@section('content')
    <div class="card">
        <div class="card-header pb-0 px-3">
            <h5 class="mb-0">{{ __('Edit User') }}</h5>
        </div>
        <div class="card-body pt-4 p-3">
            <form action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-md-6">
                        <div class="">
                            <label for="user-name" class="form-control-label">{{ __('Nama / Panggilan') }}</label>
                            <div class="@error('name')border border-danger rounded-3 @enderror">
                                <input class="form-control" type="text" placeholder="Name" name="name" value="{{ $user->name }}">
                                @error('name')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email" class="form-control-label">{{ __('Email') }}</label>
                            <div class="@error('email')border border-danger rounded-3 @enderror">
                                <input class="form-control" type="text" placeholder="xxxx@gmail.com" name="email" value="{{ $user->email }}">
                                @error('email')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email" class="form-control-label">{{ __('Password') }}</label>
                            <div class="@error('password')border border-danger rounded-3 @enderror">
                                <input class="form-control" type="password" placeholder="password" name="password" value="">
                                @error('password')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email" class="form-control-label">{{ __('Nomor telepon / WA') }}</label>
                            <div class="@error('notelp')border border-danger rounded-3 @enderror">
                                <input class="form-control" type="number" placeholder="082192xxx" name="notelp" value="{{ $user->phone }}">
                                @error('notelp')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kategori" class="form-control-label">{{ __('Role') }}</label>
                            <div class="@error('role')border border-danger rounded-3 @enderror">
                                <select name="role" id="" class="form-control">
                                    <option value="" selected disabled>Pilih Role</option>
                                    @foreach ($role as $item)
                                        <option value="{{ $item->name }}" {{ $user->roles->first()->name == $item->name ? 'selected' : '' }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                @error('role')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status" class="form-control-label">{{ __('Status') }}</label>
                            <div class="@error('status')border border-danger rounded-3 @enderror">
                                <select name="status" id="" class="form-control">
                                    <option value="" disabled>- Pilih Status -</option>
                                    <option value="aktif" {{ $user->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="tidak aktif" {{ $user->status == 'tidak aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                                @error('status')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ 'Edit User' }}</button>
                </div>
            </form>

        </div>
    </div>

@endsection
