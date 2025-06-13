<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <h4 class="panel-title">
                    <i class="fas fa-clock"></i> Tipsoi Device Cron Job Logs
                </h4>
                <div class="panel-actions">
                    <a href="<?= base_url('tipsoi_device/index') ?>" class="btn btn-sm btn-default">
                        <i class="fas fa-arrow-left"></i> Back to Device Management
                    </a>
                </div>
            </header>
            <div class="panel-body">
                <!-- Cron Job Controls -->
                <div class="row mb-lg">
                    <div class="col-md-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h4 class="panel-title">Manual Cron Testing</h4>
                            </div>
                            <div class="panel-body">
                                <p>Test cron jobs manually to verify functionality:</p>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary" onclick="testCron('24h')">
                                        <i class="fas fa-clock"></i> Test 24h Sync
                                    </button>
                                    <button type="button" class="btn btn-warning" onclick="testCron('monthly')">
                                        <i class="fas fa-calendar"></i> Test Monthly Sync
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h4 class="panel-title">Cron Job URLs</h4>
                            </div>
                            <div class="panel-body">
                                <p><strong>24h Sync (Every 10 minutes):</strong></p>
                                <code><?= base_url('tipsoi_device/cron_sync_24hours?cron_key=tipsoi_cron_' . md5('d8dd-cfbf-fa81-e1b8-54ee-fbbe-cb7a-8026-48d7-1653-4942-f499-6b04-a0b9-89c9-7309')) ?></code>
                                
                                <p class="mt-md"><strong>Monthly Sync (1st of each month):</strong></p>
                                <code><?= base_url('tipsoi_device/cron_sync_monthly?cron_key=tipsoi_cron_' . md5('d8dd-cfbf-fa81-e1b8-54ee-fbbe-cb7a-8026-48d7-1653-4942-f499-6b04-a0b9-89c9-7309')) ?></code>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cron Statistics -->
                <?php 
                $stats = isset($cron_stats) ? $cron_stats : null;
                if (!$stats) {
                    // Get stats from model if not provided
                    $this->load->model('tipsoi_device_model');
                    $stats = $this->tipsoi_device_model->get_cron_stats(30);
                }
                ?>
                <div class="row mb-lg">
                    <div class="col-md-3">
                        <div class="panel panel-success">
                            <div class="panel-body text-center">
                                <h3><?= $stats['24h_sync']['total_runs'] ?: '0' ?></h3>
                                <p>24h Sync Runs (30 days)</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="panel panel-info">
                            <div class="panel-body text-center">
                                <h3><?= $stats['24h_sync']['total_synced'] ?: '0' ?></h3>
                                <p>24h Records Synced</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="panel panel-warning">
                            <div class="panel-body text-center">
                                <h3><?= $stats['monthly_sync']['total_runs'] ?: '0' ?></h3>
                                <p>Monthly Sync Runs</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="panel panel-danger">
                            <div class="panel-body text-center">
                                <h3><?= ($stats['24h_sync']['total_errors'] + $stats['monthly_sync']['total_errors']) ?: '0' ?></h3>
                                <p>Total Errors</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cron Logs Table -->
                <?php if (!empty($cron_logs)): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="cronLogsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cron Type</th>
                                <th>Execution Time</th>
                                <th>Synced Records</th>
                                <th>Errors</th>
                                <th>Success Rate</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cron_logs as $log): ?>
                            <?php 
                            $total_records = $log['total_synced'] + $log['total_errors'];
                            $success_rate = $total_records > 0 ? round(($log['total_synced'] / $total_records) * 100, 1) : 100;
                            ?>
                            <tr>
                                <td><?= $log['id'] ?></td>
                                <td>
                                    <?php if ($log['cron_type'] == '24h_sync'): ?>
                                        <span class="label label-primary">
                                            <i class="fas fa-clock"></i> 24h Sync
                                        </span>
                                    <?php else: ?>
                                        <span class="label label-warning">
                                            <i class="fas fa-calendar"></i> Monthly Sync
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('M d, Y H:i:s', strtotime($log['execution_time'])) ?></td>
                                <td>
                                    <span class="badge badge-success"><?= $log['total_synced'] ?></span>
                                </td>
                                <td>
                                    <span class="badge badge-danger"><?= $log['total_errors'] ?></span>
                                </td>
                                <td>
                                    <?php if ($success_rate >= 80): ?>
                                        <span class="text-success"><?= $success_rate ?>%</span>
                                    <?php elseif ($success_rate >= 50): ?>
                                        <span class="text-warning"><?= $success_rate ?>%</span>
                                    <?php else: ?>
                                        <span class="text-danger"><?= $success_rate ?>%</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($log['details'])): ?>
                                    <button type="button" class="btn btn-xs btn-info" 
                                            onclick="showCronDetails(<?= $log['id'] ?>, '<?= htmlspecialchars($log['details']) ?>')">
                                        <i class="fas fa-eye"></i> View Details
                                    </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle fa-3x mb-md"></i>
                    <h4>No Cron Logs Available</h4>
                    <p>No cron jobs have been executed yet. Set up your cron jobs to start automatic synchronization.</p>
                </div>
                <?php endif; ?>

                <!-- Cron Setup Instructions -->
                <div class="row mt-lg">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">Cron Job Setup Instructions</h4>
                            </div>
                            <div class="panel-body">
                                <h5>Server Cron Configuration:</h5>
                                <p>Add these lines to your server's crontab (crontab -e):</p>
                                
                                <div class="well">
                                    <strong>24-Hour Sync (Every 10 minutes):</strong><br>
                                    <code>*/10 * * * * curl -s "<?= base_url('tipsoi_device/cron_sync_24hours?cron_key=tipsoi_cron_' . md5('d8dd-cfbf-fa81-e1b8-54ee-fbbe-cb7a-8026-48d7-1653-4942-f499-6b04-a0b9-89c9-7309')) ?>" > /dev/null 2>&1</code>
                                </div>
                                
                                <div class="well">
                                    <strong>Monthly Sync (1st day of each month at 2 AM):</strong><br>
                                    <code>0 2 1 * * curl -s "<?= base_url('tipsoi_device/cron_sync_monthly?cron_key=tipsoi_cron_' . md5('d8dd-cfbf-fa81-e1b8-54ee-fbbe-cb7a-8026-48d7-1653-4942-f499-6b04-a0b9-89c9-7309')) ?>" > /dev/null 2>&1</code>
                                </div>

                                <h5>Features:</h5>
                                <ul>
                                    <li><strong>First Attendance Only:</strong> Only the earliest attendance record per student per day is processed</li>
                                    <li><strong>Auto Sync Branches:</strong> 24h sync only processes branches with auto sync enabled</li>
                                    <li><strong>Monthly Archive:</strong> Monthly sync processes all branches for historical data</li>
                                    <li><strong>Duplicate Prevention:</strong> Existing attendance records are not overwritten</li>
                                    <li><strong>Error Logging:</strong> All sync operations are logged with detailed error information</li>
                                </ul>

                                <div class="alert alert-warning">
                                    <strong>Important:</strong> Ensure your server has curl installed and can access the internet to reach the API endpoints.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Cron Details Modal -->
<div class="modal fade" id="cronDetailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Cron Job Execution Details</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Cron Log ID:</label>
                    <span id="cronLogId"></span>
                </div>
                <div class="form-group">
                    <label>Execution Details:</label>
                    <div class="well">
                        <pre id="cronDetailsContent" style="max-height: 400px; overflow-y: auto;"></pre>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="downloadCronLog()">
                    <i class="fas fa-download"></i> Download Log
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTable
    if ($.fn.DataTable) {
        $('#cronLogsTable').DataTable({
            "order": [[ 0, "desc" ]],
            "pageLength": 25,
            "responsive": true
        });
    }
});

var currentCronData = '';

function testCron(type) {
    $.ajax({
        url: '<?= base_url("tipsoi_device/test_cron") ?>',
        type: 'GET',
        data: { type: type },
        dataType: 'json',
        beforeSend: function() {
            $('button').prop('disabled', true);
            toastr.info('Running ' + type + ' cron job...');
        },
        success: function(response) {
            if (response.status === 'success') {
                toastr.success(response.message);
                // Reload page after 2 seconds to show new logs
                setTimeout(function() {
                    location.reload();
                }, 2000);
            } else {
                toastr.error(response.message);
            }
        },
        error: function() {
            toastr.error('Failed to execute cron job');
        },
        complete: function() {
            $('button').prop('disabled', false);
        }
    });
}

function showCronDetails(logId, details) {
    try {
        var detailsObj = JSON.parse(details);
        var detailsText = JSON.stringify(detailsObj, null, 2);
        $('#cronLogId').text(logId);
        $('#cronDetailsContent').text(detailsText);
        currentCronData = detailsText;
        $('#cronDetailsModal').modal('show');
    } catch (e) {
        $('#cronLogId').text(logId);
        $('#cronDetailsContent').text(details);
        currentCronData = details;
        $('#cronDetailsModal').modal('show');
    }
}

function downloadCronLog() {
    var element = document.createElement('a');
    element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(currentCronData));
    element.setAttribute('download', 'tipsoi_cron_log_' + new Date().toISOString().split('T')[0] + '.txt');
    element.style.display = 'none';
    document.body.appendChild(element);
    element.click();
    document.body.removeChild(element);
}
</script> 