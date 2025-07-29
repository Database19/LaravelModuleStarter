@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Email Settings</h3>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mail_driver">Mail Driver</label>
                                    <select class="form-control" id="mail_driver" name="mail_driver">
                                        <option value="smtp" selected>SMTP</option>
                                        <option value="sendmail">Sendmail</option>
                                        <option value="mailgun">Mailgun</option>
                                        <option value="ses">Amazon SES</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mail_host">SMTP Host</label>
                                    <input type="text" class="form-control" id="mail_host" name="mail_host"
                                           value="smtp.gmail.com">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mail_port">SMTP Port</label>
                                    <input type="number" class="form-control" id="mail_port" name="mail_port"
                                           value="587">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mail_encryption">Encryption</label>
                                    <select class="form-control" id="mail_encryption" name="mail_encryption">
                                        <option value="tls" selected>TLS</option>
                                        <option value="ssl">SSL</option>
                                        <option value="">None</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mail_username">SMTP Username</label>
                                    <input type="email" class="form-control" id="mail_username" name="mail_username"
                                           value="your-email@gmail.com">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mail_password">SMTP Password</label>
                                    <input type="password" class="form-control" id="mail_password" name="mail_password"
                                           value="••••••••••••">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mail_from_address">From Address</label>
                                    <input type="email" class="form-control" id="mail_from_address" name="mail_from_address"
                                           value="noreply@example.com">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mail_from_name">From Name</label>
                                    <input type="text" class="form-control" id="mail_from_name" name="mail_from_name"
                                           value="PT. Example Company">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Save Email Settings
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-info">
                                        <i class="fas fa-paper-plane"></i> Send Test Email
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
