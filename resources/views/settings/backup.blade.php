@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Backup & Restore</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Backup Section -->
                        <div class="col-md-6">
                            <h4>Create Backup</h4>
                            <p class="text-muted">Create a backup of your database and files.</p>

                            <form>
                                <div class="form-group">
                                    <label>Backup Type</label>
                                    <div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="backup_db" name="backup_type[]" value="database" checked>
                                            <label class="form-check-label" for="backup_db">
                                                Database
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="backup_files" name="backup_type[]" value="files">
                                            <label class="form-check-label" for="backup_files">
                                                Files & Uploads
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="backup_name">Backup Name</label>
                                    <input type="text" class="form-control" id="backup_name" name="backup_name"
                                           value="backup-{{ date('Y-m-d-H-i-s') }}">
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-download"></i> Create Backup
                                </button>
                            </form>
                        </div>

                        <!-- Backup History -->
                        <div class="col-md-6">
                            <h4>Backup History</h4>
                            <p class="text-muted">Previous backups available for download or restore.</p>

                            <div class="list-group">
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">backup-2025-07-26-14-30-00.sql</h6>
                                        <small>2 hours ago</small>
                                    </div>
                                    <p class="mb-1">Database backup - 15.2 MB</p>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary">
                                            <i class="fas fa-download"></i> Download
                                        </button>
                                        <button class="btn btn-outline-warning">
                                            <i class="fas fa-undo"></i> Restore
                                        </button>
                                        <button class="btn btn-outline-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </div>
                                </div>

                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">backup-2025-07-25-09-15-30.sql</h6>
                                        <small>1 day ago</small>
                                    </div>
                                    <p class="mb-1">Database backup - 14.8 MB</p>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary">
                                            <i class="fas fa-download"></i> Download
                                        </button>
                                        <button class="btn btn-outline-warning">
                                            <i class="fas fa-undo"></i> Restore
                                        </button>
                                        <button class="btn btn-outline-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="alert alert-warning">
                                <h5><i class="fas fa-exclamation-triangle"></i> Important Notes:</h5>
                                <ul>
                                    <li>Always test restores on a non-production environment first</li>
                                    <li>Database restores will overwrite existing data</li>
                                    <li>Large backups may take several minutes to complete</li>
                                    <li>Schedule automatic backups for production systems</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
