@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-primary">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="card-title fw-semibold text-white">Setting Midtrans Key</h5>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="alert alert-warning" role="alert">
                        Kosongkan jika tidak ingin mengaktifkan fitur Payment gateway Midtrans !
                    </div>

                    @if (session()->has('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="/settings-midtrans" method="POST">
                        @method('put')
                        @csrf

                        <div class="mb-3">
                            <label for="midtrans_merchant_id" class="form-label">Midtrans Merchant Id</label>
                            <input type="text" class="form-control" name="midtrans_merchant_id"
                                value="{{ old('midtrans_merchant_id', $midtrans_merchant_id) }}">
                            @error('midtrans_merchant_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="midtrans_client_key" class="form-label">Midtrans Client Key</label>
                            <input type="text" class="form-control" name="midtrans_client_key"
                                value="{{ old('midtrans_client_key', $midtrans_client_key) }}">
                            @error('midtrans_client_key')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="midtrans_server_key" class="form-label">Midtrans Server Key</label>
                            <input type="text" class="form-control" name="midtrans_server_key"
                                value="{{ old('midtrans_server_key', $midtrans_server_key) }}">
                            @error('midtrans_server_key')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary m-1 float-end">Update</button>
                    </form>


                </div>
            </div>
        </div>
    </div>
@endsection
