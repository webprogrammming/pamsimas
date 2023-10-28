@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card overflow-hidden bg-primary text-white">
                <div class="card-body p-4 bg-primary">
                    <div class="row">
                      <div class="col-lg-6">
                        <h2 class="card-title my-5 fw-semibold text-white">Selamat Datang {{ auth()->user()->name }}</h2>
                      </div>
                      <div class="col-lg-6">
                        <img src="/assets/images/dashboard/welcome.svg" alt="Selamat Datang" style="width: 300px;" class="float-end">
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (auth()->user()->role->role == 'pelanggan')
      <div class="row">
        <div class="col-lg-4">
          <div class="card overflow-hidden bg-danger text-white">
            <div class="card-body p-4">
              <h5 class="card-title mb-9 fw-semibold text-white">Tagihan Belum Dibayar</h5>
              <div class="row align-items-center">
                <div class="col-8">
                  <h4 class="fw-semibold mb-3 text-white">12</h4>
                </div>
                <div class="col-4">
                  <div class="d-flex justify-content-center">
                      <i class="ti ti-user-check" style="font-size: 2rem;"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      
        <div class="col-lg-4">
          <div class="card overflow-hidden bg-success text-white">
            <div class="card-body p-4">
              <h5 class="card-title mb-9 fw-semibold text-white">Pemakaian Periode Ini</h5>
              <div class="row align-items-center">
                <div class="col-8">
                  <h4 class="fw-semibold mb-3 text-white">35</h4>
                </div>
                <div class="col-4">
                  <div class="d-flex justify-content-center">
                      <i class="ti ti-edit" style="font-size: 2rem;"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-lg-4">
          <div class="card overflow-hidden bg-warning text-white">
            <div class="card-body p-4">
              <h5 class="card-title mb-9 fw-semibold text-white">Jumlah Pelanggan</h5>
              <div class="row align-items-center">
                <div class="col-8">
                  <h4 class="fw-semibold mb-3 text-white">123</h4>
                </div>
                <div class="col-4">
                  <div class="d-flex justify-content-center">
                      <i class="ti ti-building-store" style="font-size: 2rem;"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
    @else
    <div class="row">
      <div class="col-lg-4">
        <div class="card overflow-hidden bg-danger text-white">
          <div class="card-body p-4">
            <h5 class="card-title mb-9 fw-semibold text-white">Tagihan Belum Dibayar</h5>
            <div class="row align-items-center">
              <div class="col-8">
                <h4 class="fw-semibold mb-3 text-white">12</h4>
              </div>
              <div class="col-4">
                <div class="d-flex justify-content-center">
                    <i class="ti ti-user-check" style="font-size: 2rem;"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    
      <div class="col-lg-4">
        <div class="card overflow-hidden bg-success text-white">
          <div class="card-body p-4">
            <h5 class="card-title mb-9 fw-semibold text-white">Pemakaian Periode Ini</h5>
            <div class="row align-items-center">
              <div class="col-8">
                <h4 class="fw-semibold mb-3 text-white">35</h4>
              </div>
              <div class="col-4">
                <div class="d-flex justify-content-center">
                    <i class="ti ti-edit" style="font-size: 2rem;"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-lg-4">
        <div class="card overflow-hidden bg-warning text-white">
          <div class="card-body p-4">
            <h5 class="card-title mb-9 fw-semibold text-white">Jumlah Pelanggan</h5>
            <div class="row align-items-center">
              <div class="col-8">
                <h4 class="fw-semibold mb-3 text-white">123</h4>
              </div>
              <div class="col-4">
                <div class="d-flex justify-content-center">
                    <i class="ti ti-building-store" style="font-size: 2rem;"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  </div>
    @endif
@endsection