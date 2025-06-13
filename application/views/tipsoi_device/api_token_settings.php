<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <h4 class="panel-title"><i class="fas fa-key"></i> <?= html_escape($title) ?></h4>
            </header>
            <div class="panel-body">
                <p class="mb-md">Manage the Tipsoi API tokens for each branch individually. If a token is not set for a specific branch, the system will use the default API token.</p>
                
                <?php if (empty($branch_configs)): ?>
                    <div class="alert alert-info">No branches found to configure.</div>
                <?php else: ?>
                    <?php foreach ($branch_configs as $branch_id => $config): ?>
                        <?php echo form_open(base_url('tipsoi_device/save_branch_api_token'), ['class' => 'form-horizontal mb-lg']); ?>
                            <input type="hidden" name="branch_id" value="<?= html_escape($branch_id) ?>">
                            
                            <div class="form-group">
                                <label class="col-md-3 control-label">
                                    Branch: <strong><?= html_escape($config['name']) ?></strong>
                                    <br>
                                    <small class="text-muted">(ID: <?= html_escape($branch_id) ?>)</small>
                                </label>
                                <div class="col-md-7">
                                    <input type="text" name="api_token" class="form-control" 
                                           value="<?= html_escape($config['api_token']) ?>" 
                                           placeholder="Enter API Token for <?= html_escape($config['name']) ?> (leave empty for default)">
                                    <span class="help-block">
                                        Device Name (auto-set if new): <code><?= html_escape($config['device_name']) ?></code>
                                    </span>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" name="save_api_token" value="1" class="btn btn-primary btn-block">
                                        <i class="fas fa-save"></i> Save Token
                                    </button>
                                </div>
                            </div>
                        <?php echo form_close(); ?>
                        <hr class="dotted short">
                    <?php endforeach; ?>
                <?php endif; ?>
                
                <div class="mt-lg">
                    <a href="<?= base_url('tipsoi_device') ?>" class="btn btn-default">
                        <i class="fas fa-arrow-left"></i> Back to Tipsoi Device Management
                    </a>
                </div>
            </div>
        </section>
    </div>
</div>

<style>
.mb-lg {
    margin-bottom: 20px !important;
}
.mt-lg {
    margin-top: 20px !important;
}
.form-horizontal .control-label {
    text-align: left;
}
</style> 