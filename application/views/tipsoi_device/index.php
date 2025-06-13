<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <h4 class="panel-title">
                    <i class="fas fa-microchip"></i> Tipsoi Device Management
                </h4>
            </header>
            <div class="panel-body">
                <!-- Device Status Card -->
                <div class="row" style="margin-bottom: 20px;">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <h4><i class="fas fa-info-circle"></i> Device Status</h4>
                            <p>This module synchronizes attendance data from Tipsoi devices via API integration with api-inovace360.com</p>
                            <p><strong>Current API Token:</strong> 
                                <?php if (isset($device_config['api_token']) && !empty($device_config['api_token'])): ?>
                                    <span class="text-success"><?= substr($device_config['api_token'], 0, 20) ?>...</span> (Branch-specific)
                                <?php else: ?>
                                    <span class="text-warning">Using default token</span> (Configure branch-specific token below)
                                <?php endif; ?>
                            </p>
                            <a href="<?= base_url('tipsoi_device/api_token_settings') ?>" class="btn btn-sm btn-default">
                                <i class="fas fa-key"></i> Manage Branch API Tokens
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Sync Section -->
                <div class="row" style="margin-bottom: 20px;">
                    <div class="col-md-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h4 class="panel-title">Quick Sync</h4>
                            </div>
                            <div class="panel-body">
                                <?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal')); ?>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Sync Date</label>
                                    <div class="col-md-9">
                                        <input type="date" name="sync_date" class="form-control" value="<?=date('Y-m-d')?>" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" name="sync_attendance" value="1" class="btn btn-primary">
                                            <i class="fas fa-sync"></i> Sync Attendance
                                        </button>
                                        <button type="button" class="btn btn-info" onclick="testApiConnection()">
                                            <i class="fas fa-plug"></i> Test API Connection
                                        </button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h4 class="panel-title">Sync Statistics</h4>
                            </div>
                            <div class="panel-body">
                                <?php 
                                $stats = isset($sync_logs) && !empty($sync_logs) ? $sync_logs[0] : null;
                                ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="text-center">
                                            <h3 class="text-success"><?= $stats ? $stats['synced_count'] : '0' ?></h3>
                                            <p>Last Synced</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-center">
                                            <h3 class="text-danger"><?= $stats ? $stats['error_count'] : '0' ?></h3>
                                            <p>Errors</p>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($stats): ?>
                                <p class="text-muted">Last sync: <?= date('M d, Y H:i', strtotime($stats['created_at'])) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Sync Logs -->
                <div class="row" style="margin-bottom: 20px;">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading clearfix">
                                <h4 class="panel-title pull-left">Recent Sync Logs</h4>
                                <div class="pull-right">
                                    <a href="<?= base_url('tipsoi_device/view_sync_logs') ?>" class="btn btn-sm btn-primary">
                                        View All Logs
                                    </a>
                                    <a href="<?= base_url('tipsoi_device/cron_logs') ?>" class="btn btn-sm btn-warning">
                                        <i class="fas fa-clock"></i> Cron Logs
                                    </a>
                                </div>
                            </div>
                            <div class="panel-body">
                                <?php if (!empty($sync_logs)): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Synced</th>
                                                <th>Errors</th>
                                                <th>Time</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($sync_logs as $log): ?>
                                            <tr>
                                                <td><?= date('M d, Y', strtotime($log['sync_date'])) ?></td>
                                                <td>
                                                    <?php if ($log['status'] == 'success'): ?>
                                                        <span class="label label-success">Success</span>
                                                    <?php elseif ($log['status'] == 'partial'): ?>
                                                        <span class="label label-warning">Partial</span>
                                                    <?php else: ?>
                                                        <span class="label label-danger">Failed</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><span class="badge badge-success"><?= $log['synced_count'] ?></span></td>
                                                <td><span class="badge badge-danger"><?= $log['error_count'] ?></span></td>
                                                <td><?= date('H:i:s', strtotime($log['created_at'])) ?></td>
                                                <td>
                                                    <?php if (!empty($log['errors']) && $log['errors'] != '[]'): ?>
                                                    <button type="button" class="btn btn-xs btn-info" 
                                                            onclick="showErrors('<?= htmlspecialchars($log['errors']) ?>')">
                                                        View Errors
                                                    </button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php else: ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> No sync logs available. Perform your first sync to see logs here.
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- API Integration Notes -->
                <div class="row" style="margin-bottom: 20px;">
                    <div class="col-md-12">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h4 class="panel-title">API Integration Notes</h4>
                            </div>
                            <div class="panel-body">
                                <h5>How it works:</h5>
                                <ul>
                                    <li><strong>API Integration:</strong> All attendance data is fetched from <code>api-inovace360.com</code> - no direct device connection needed.</li>
                                    <li><strong>Person Identifier Matching:</strong> The system matches <code>person_identifier</code> from the API with <code>register_no</code> in the student database.</li>
                                    <li><strong>Attendance Status:</strong> Based on the timestamp from the device, attendance status is automatically determined (Present, Late, Half Day).</li>
                                    <li><strong>API Endpoint:</strong> <code>https://api-inovace360.com/attendance_logs</code> (GET request with query parameters)</li>
                                    <li><strong>Authentication:</strong> API token passed as query parameter for secure API access.</li>
                                    <li><strong>First Attendance Only:</strong> Only the earliest attendance record per student per day is processed to prevent duplicates.</li>
                                </ul>
                                <h5>Attendance Status Rules:</h5>
                                <ul>
                                    <li><strong>Present (P):</strong> Arrival before 10:00 AM</li>
                                    <li><strong>Late (L):</strong> Arrival between 10:00 AM - 12:00 PM</li>
                                    <li><strong>Half Day (HD):</strong> Arrival after 12:00 PM</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Sync Errors</h4>
            </div>
            <div class="modal-body">
                <pre id="errorContent"></pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
.panel-heading.clearfix {
    overflow: hidden;
}
.panel-heading .panel-title {
    margin: 0;
    line-height: 30px;
}
.panel-heading .pull-right {
    margin-top: 0;
}
.panel-heading .pull-right .btn {
    margin-left: 5px;
}
.mb-lg {
    margin-bottom: 20px;
}
</style>

<script>
function testApiConnection() {
    $.ajax({
        url: '<?= base_url("tipsoi_device/test_api_connection") ?>',
        type: 'GET',
        dataType: 'json',
        beforeSend: function() {
            $('button').prop('disabled', true);
            toastr.info('Testing API connection...');
        },
        success: function(response) {
            if (response.status === 'success') {
                toastr.success(response.message);
            } else {
                toastr.error(response.message);
            }
        },
        error: function() {
            toastr.error('Failed to test API connection');
        },
        complete: function() {
            $('button').prop('disabled', false);
        }
    });
}

function showErrors(errors) {
    try {
        var errorArray = JSON.parse(errors);
        var errorText = errorArray.join('\n');
        $('#errorContent').text(errorText);
        $('#errorModal').modal('show');
    } catch (e) {
        $('#errorContent').text(errors);
        $('#errorModal').modal('show');
    }
}
</script> 