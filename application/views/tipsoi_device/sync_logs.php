<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <h4 class="panel-title">
                    <i class="fas fa-history"></i> Tipsoi Device Sync Logs
                </h4>
                <div class="panel-actions">
                    <a href="<?= base_url('tipsoi_device/index') ?>" class="btn btn-sm btn-default">
                        <i class="fas fa-arrow-left"></i> Back to Device Management
                    </a>
                </div>
            </header>
            <div class="panel-body">
                <?php if (!empty($sync_logs)): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="syncLogsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Sync Date</th>
                                <th>Status</th>
                                <th>Synced Records</th>
                                <th>Error Count</th>
                                <th>Success Rate</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sync_logs as $log): ?>
                            <?php 
                            $total_records = $log['synced_count'] + $log['error_count'];
                            $success_rate = $total_records > 0 ? round(($log['synced_count'] / $total_records) * 100, 1) : 0;
                            ?>
                            <tr>
                                <td><?= $log['id'] ?></td>
                                <td><?= date('M d, Y', strtotime($log['sync_date'])) ?></td>
                                <td>
                                    <?php if ($log['status'] == 'success'): ?>
                                        <span class="label label-success">
                                            <i class="fas fa-check"></i> Success
                                        </span>
                                    <?php elseif ($log['status'] == 'partial'): ?>
                                        <span class="label label-warning">
                                            <i class="fas fa-exclamation-triangle"></i> Partial
                                        </span>
                                    <?php else: ?>
                                        <span class="label label-danger">
                                            <i class="fas fa-times"></i> Failed
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge badge-success"><?= $log['synced_count'] ?></span>
                                </td>
                                <td>
                                    <span class="badge badge-danger"><?= $log['error_count'] ?></span>
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
                                <td><?= date('M d, Y H:i:s', strtotime($log['created_at'])) ?></td>
                                <td>
                                    <?php if (!empty($log['errors']) && $log['errors'] != '[]'): ?>
                                    <button type="button" class="btn btn-xs btn-info" 
                                            onclick="showErrorDetails(<?= $log['id'] ?>, '<?= htmlspecialchars($log['errors']) ?>')">
                                        <i class="fas fa-eye"></i> View Errors
                                    </button>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-xs btn-primary" 
                                            onclick="resyncDate('<?= $log['sync_date'] ?>')">
                                        <i class="fas fa-redo"></i> Re-sync
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Summary Statistics -->
                <div class="row mt-lg">
                    <div class="col-md-3">
                        <div class="panel panel-success">
                            <div class="panel-body text-center">
                                <h3><?= array_sum(array_column($sync_logs, 'synced_count')) ?></h3>
                                <p>Total Synced Records</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="panel panel-danger">
                            <div class="panel-body text-center">
                                <h3><?= array_sum(array_column($sync_logs, 'error_count')) ?></h3>
                                <p>Total Errors</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="panel panel-info">
                            <div class="panel-body text-center">
                                <h3><?= count($sync_logs) ?></h3>
                                <p>Total Sync Operations</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="panel panel-warning">
                            <div class="panel-body text-center">
                                <?php 
                                $successful_syncs = count(array_filter($sync_logs, function($log) { 
                                    return $log['status'] == 'success'; 
                                }));
                                $overall_success_rate = count($sync_logs) > 0 ? round(($successful_syncs / count($sync_logs)) * 100, 1) : 0;
                                ?>
                                <h3><?= $overall_success_rate ?>%</h3>
                                <p>Overall Success Rate</p>
                            </div>
                        </div>
                    </div>
                </div>

                <?php else: ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle fa-3x mb-md"></i>
                    <h4>No Sync Logs Available</h4>
                    <p>No synchronization operations have been performed yet. Start by configuring your device and performing your first sync.</p>
                    <a href="<?= base_url('tipsoi_device/index') ?>" class="btn btn-primary">
                        <i class="fas fa-cog"></i> Configure Device
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>

<!-- Error Details Modal -->
<div class="modal fade" id="errorDetailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Sync Error Details</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Sync Log ID:</label>
                    <span id="errorLogId"></span>
                </div>
                <div class="form-group">
                    <label>Error Messages:</label>
                    <div class="well">
                        <pre id="errorDetailsContent" style="max-height: 300px; overflow-y: auto;"></pre>
                    </div>
                </div>
                <div class="alert alert-info">
                    <strong>Common Solutions:</strong>
                    <ul class="mb-none">
                        <li>Ensure student register numbers match the person_identifier from the device</li>
                        <li>Check if students are properly enrolled in the current session</li>
                        <li>Verify API connectivity and authentication</li>
                        <li>Check device timestamp format and timezone settings</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="downloadErrorLog()">
                    <i class="fas fa-download"></i> Download Error Log
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Re-sync Confirmation Modal -->
<div class="modal fade" id="resyncModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Confirm Re-sync</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to re-sync attendance data for <strong id="resyncDate"></strong>?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    This will overwrite any existing attendance records for this date.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" onclick="performResync()">
                    <i class="fas fa-redo"></i> Re-sync
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTable for better sorting and searching
    if ($.fn.DataTable) {
        $('#syncLogsTable').DataTable({
            "order": [[ 0, "desc" ]],
            "pageLength": 25,
            "responsive": true
        });
    }
});

var currentErrorData = '';
var currentResyncDate = '';

function showErrorDetails(logId, errors) {
    try {
        var errorArray = JSON.parse(errors);
        var errorText = errorArray.join('\n');
        $('#errorLogId').text(logId);
        $('#errorDetailsContent').text(errorText);
        currentErrorData = errorText;
        $('#errorDetailsModal').modal('show');
    } catch (e) {
        $('#errorLogId').text(logId);
        $('#errorDetailsContent').text(errors);
        currentErrorData = errors;
        $('#errorDetailsModal').modal('show');
    }
}

function downloadErrorLog() {
    var element = document.createElement('a');
    element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(currentErrorData));
    element.setAttribute('download', 'tipsoi_sync_errors_' + new Date().toISOString().split('T')[0] + '.txt');
    element.style.display = 'none';
    document.body.appendChild(element);
    element.click();
    document.body.removeChild(element);
}

function resyncDate(date) {
    currentResyncDate = date;
    $('#resyncDate').text(date);
    $('#resyncModal').modal('show');
}

function performResync() {
    $('#resyncModal').modal('hide');
    
    // Show loading indicator
    toastr.info('Starting re-sync for ' + currentResyncDate + '...');
    
    // Redirect to manual sync with the date
    window.location.href = '<?= base_url("tipsoi_device/manual_sync") ?>?date=' + currentResyncDate;
}
</script> 