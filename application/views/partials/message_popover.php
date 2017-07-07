<?=form_open($form_action);?>
<div class="form-group">
    <label class="control-label">Message</label>
    <?=form_hidden("recipients[]",$recipient_id);?>
    <?=form_hidden("target",$target);?>
    <?php if(isset($extras) && sizeof($extras) > 0){
        foreach($extras as $k=>$v){
            echo form_hidden($k,$v);
        }
    } ?>
    <textarea class="form-control" required="required" name="message"></textarea>
</div>
<div class="text-center">
    <?=form_submit(array("class"=>"btn btn-primary btn-xs ","value"=>"Send"));?>
    <?=form_reset(array("class"=>"btn btn-warning btn-xs","value"=>"Reset"));?>
</div>
<?=form_close();?>