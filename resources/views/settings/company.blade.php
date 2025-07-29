@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Company Settings</h3>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company_name">Company Name</label>
                                    <input type="text" class="form-control" id="company_name" name="company_name"
                                           value="PT. Example Company" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company_code">Company Code</label>
                                    <input type="text" class="form-control" id="company_code" name="company_code"
                                           value="EXAMPLE" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <textarea class="form-control" id="address" name="address" rows="3">Jl. Example Street No. 123
Jakarta, Indonesia</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                           value="+62-21-12345678">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                           value="info@example.com">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="website">Website</label>
                                    <input type="url" class="form-control" id="website" name="website"
                                           value="https://www.example.com">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tax_id">Tax ID / NPWP</label>
                                    <input type="text" class="form-control" id="tax_id" name="tax_id"
                                           value="12.345.678.9-012.000">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="currency">Default Currency</label>
                                    <select class="form-control" id="currency" name="currency">
                                        <option value="IDR" selected>IDR - Indonesian Rupiah</option>
                                        <option value="USD">USD - US Dollar</option>
                                        <option value="EUR">EUR - Euro</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="timezone">Timezone</label>
                                    <select class="form-control" id="timezone" name="timezone">
                                        <option value="Asia/Jakarta" selected>Asia/Jakarta (WIB)</option>
                                        <option value="Asia/Makassar">Asia/Makassar (WITA)</option>
                                        <option value="Asia/Jayapura">Asia/Jayapura (WIT)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Company Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
