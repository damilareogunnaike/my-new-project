<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="<?=(!isset($active_tab)) ? "active" : '';?>">
                <?=anchor('#principal_comment','Principal\'s Comment',array('data-toggle'=>"tab"));?>
            </li>
        </ul>
        
        <!-- Status Message -->
        <?=isset($msg) ? $msg : '';?>
        
        <!-- Tab Content -->
        <div class="tab-content">
            <div class="tab-pane <?=(!isset($active_tab)) ? "active" : '';?>" id="principal_comment">
                <div class="panel panel-default">
                     <div class="panel-heading ">
                         <h3 class="panel-title text-center text-uppercase">Principal's Comment</h3>
                    </div>
                    <div class="panel-body">
                        <table class="table-bordered table">
                            <thead>
                            <th>#</th>
                            <th>Start Average (%)</th>
                            <th>End Average  (%)</th>
                            <th>Comment</th>
                            <th></th>
                            </thead>
                            <tbody>
                               <?php
                               if(isset($p_comments) && sizeof($p_comments) > 0)
                               {
                                   $count = 0;
                                   foreach($p_comments as $comment){
                                       ?>
                                <tr>
                                    <td><?=++$count;?></td>
                                    <td><?=$comment['start_average'];?></td>
                                    <td><?=$comment['end_average'];?></td>
                                    <td><?=$comment['comment'];?></td>
                                    <td>
                                        <?=anchor('admin/del_p_comment/'.$comment['comment_id'],'<i class="fa fa-trash-o"></i>',array('class'=>'text-danger text-sm'));?>
                                    </td>
                                </tr>
                                <?php
                                   }
                               }
                               ?>
                            </tbody>
                        </table>
                        <hr>
                         <?=form_open('admin/add_p_comment',array('rel'=>'async','action'=>site_url('admin/add_c_skill')));?>
                            <div class="row form-group">
                                <div class="col-md-2">
                                    <?=form_input(array('class'=>'form-control input','name'=>'start_average','required'=>'required','placeholder'=>'Start Average'))?>
                                </div>
                                <div class="col-md-2">
                                    <?=form_input(array('class'=>'form-control input','name'=>'end_average','required'=>'required','placeholder'=>'End Average'))?>
                                </div>
                                <div class="col-md-6">
                                    <?=form_input(array('class'=>'form-control input','name'=>'comment','required'=>'required','placeholder'=>'Comment'))?>
                                </div>
                                <div class="col-md-2">
                                    <?=form_submit(array('class'=>'form-control btn btn-primary','value'=>'Add'));?>
                                </div>
                            </div>
                            <?=form_close();?>
                    </div>
                    <div class="panel-footer">
                         <div class="row">
                            <div class="col-md-8 col-md-offset-2 text-right">
                            </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Tab Content -->
    </div>
</div>