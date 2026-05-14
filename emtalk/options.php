<?php if (!defined('EMLOG_ROOT')) {exit('error!');} ?>
<div class="container">
    <div class="page-header">
        <h2>微话主题设置</h2>
    </div>
    
    <form action="?action=config" method="post" class="config-form">
        <div class="form-group">
            <label for="logo_text">站点名称</label>
            <input type="text" id="logo_text" name="logo_text" value="<?php echo $options['logo_text']; ?>" class="form-control">
        </div>
        
        <div class="form-group">
            <label for="footer_info">底部信息</label>
            <input type="text" id="footer_info" name="footer_info" value="<?php echo $options['footer_info']; ?>" class="form-control">
        </div>
        
        <div class="form-group">
            <label for="icp">ICP备案号</label>
            <input type="text" id="icp" name="icp" value="<?php echo $options['icp']; ?>" class="form-control">
        </div>
        
        <div class="form-group">
            <label for="stat_code">统计代码</label>
            <textarea id="stat_code" name="stat_code" class="form-control" rows="4"><?php echo $options['stat_code']; ?></textarea>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">保存设置</button>
        </div>
    </form>
</div>
<style>
.container { max-width: 800px; margin: 0 auto; padding: 20px; }
.page-header { margin-bottom: 30px; }
.config-form .form-group { margin-bottom: 20px; }
.config-form label { display: block; margin-bottom: 8px; font-weight: 500; }
.config-form .form-control { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; }
.config-form textarea.form-control { resize: vertical; min-height: 100px; }
.config-form .form-actions { margin-top: 30px; }
.config-form .btn { padding: 10px 24px; background: #6200ee; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; }
.config-form .btn:hover { background: #4b00b8; }
</style>
