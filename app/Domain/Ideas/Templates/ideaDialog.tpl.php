<?php
foreach ($__data as $var => $val) {
    $$var = $val; // necessary for blade refactor
}
$canvasItem = $tpl->get('canvasItem');
$canvasTypes = $tpl->get('canvasTypes');

$id = "";
if (isset($canvasItem['id']) && $canvasItem['id'] != '') {
    $id = $canvasItem['id'];
}
?>

<script type="text/javascript">
    window.onload = function () {
        if (!window.jQuery) {
            //It's not a modal
            location.href = "<?=BASE_URL ?>/ideas/showBoards?showIdeaModal=<?php echo $canvasItem['id']; ?>";
        }
    }
</script>

<?php echo $tpl->displayNotification(); ?>


<form class="formModal" method="post" action="<?=BASE_URL ?>/ideas/ideaDialog/<?php echo $id; ?>">


<div class="row">

    <div class="col-md-8">

        <input type="hidden" value="<?php echo $tpl->get('currentCanvas'); ?>" name="canvasId"/>
        <input type="hidden" value="<?php $tpl->e($canvasItem['box']) ?>" name="box" id="box"/>
        <input type="hidden" value="<?php echo $id ?>" name="itemId" id="itemId"/>
        <input type="hidden" name="status" value="<?php echo $canvasItem['status'] ?>" />

        <input type="hidden" name="milestoneId" value="<?php echo $canvasItem['milestoneId'] ?>"/>
        <input type="hidden" name="changeItem" value="1"/>

        <input type="text" name="description" class="main-title-input" style="width:99%;" value="<?php $tpl->e($canvasItem['description']); ?>"
               placeholder="<?php echo $tpl->__("input.placeholders.short_name") ?>"/><br/>

        <input type="text" value="<?php $tpl->e($canvasItem['tags']); ?>" name="tags" id="tags" />


        <textarea rows="3" cols="10" name="data" class="complexEditor"
                  placeholder=""><?=htmlentities($canvasItem['data']) ?></textarea><br/>

        <input type="submit" value="<?php echo $tpl->__("buttons.save")?>" id="primaryCanvasSubmitButton"/>
        <button class="btn btn-primary" type="submit" value="closeModal" id="saveAndClose"><?php echo $tpl->__("buttons.save_and_close")?></button>

        <?php if ($id !== '') { ?>
            <br/>
            <hr>
            <input type="hidden" name="comment" value="1"/>

            <h4 class="widgettitle title-light"><span class="fa fa-comments"></span><?php echo $tpl->__('subtitles.discussion'); ?></h4>
            <?php
            $tpl->assign("formUrl", BASE_URL . "/ideas/ideaDialog/" . $id . "");

            $tpl->displaySubmodule('comments-generalComment'); ?>
        <?php } ?>

    </div>

    <div class="col-md-4">
        <?php if ($id !== '') { ?>
            <br/><br/>
            <h4 class="widgettitle title-light"><span
                    class="fas fa-map"></span> <?php echo $tpl->__("headlines.attached_milestone") ?></h4>


            <ul class="sortableTicketList" style="width:99%">
                <?php if ($canvasItem['milestoneId'] == '') { ?>
                    <li class="ui-state-default center" id="milestone_0">
                        <h4><?php echo $tpl->__("headlines.no_milestone_attached") ?></h4>
                        <?php echo $tpl->__("text.use_milestone_to_track_idea") ?><br/>
                        <div class="row" id="milestoneSelectors">
                            <?php if ($login::userIsAtLeast($roles::$editor)) { ?>
                                <div class="col-md-12">
                                    <a href="javascript:void(0);"
                                       onclick="leantime.ideasController.toggleMilestoneSelectors('new');"><?php echo $tpl->__("links.create_attach_milestone") ?></a>
                                    | <a href="javascript:void(0);"
                                         onclick="leantime.ideasController.toggleMilestoneSelectors('existing');"><?php echo $tpl->__("links.attach_existing_milestone") ?></a>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="row" id="newMilestone" style="display:none;">
                            <div class="col-md-12">
                                <textarea name="newMilestone"></textarea><br/>
                                <input type="hidden" name="type" value="milestone"/>
                                <input type="hidden" name="leancanvasitemid" value="<?php echo $id; ?> "/>
                                <input type="button" value="<?php echo $tpl->__("buttons.save")?>" onclick="jQuery('#primaryCanvasSubmitButton').click()"
                                       class="btn btn-primary"/>
                                <a href="javascript:void(0);"
                                   onclick="leantime.ideasController.toggleMilestoneSelectors('hide');">
                                    <i class="fas fa-times"></i> <?php echo $tpl->__("links.cancel") ?>
                                </a>
                            </div>
                        </div>

                        <div class="row" id="existingMilestone" style="display:none;">
                            <div class="col-md-12">
                                <select data-placeholder="<?php echo $tpl->__("input.placeholders.filter_by_milestone") ?>"
                                        name="existingMilestone" class="user-select">
                                    <option value=""><?php echo $tpl->__("text.all_milestones")?></option>
                                    <?php foreach ($tpl->get('milestones') as $milestoneRow) {
                                        echo "<option value='" . $milestoneRow->id . "'";

                                        if (isset($searchCriteria['milestone']) && ($searchCriteria['milestone'] == $milestoneRow->id)) {
                                            echo " selected='selected' ";
                                        }

                                        echo ">" . $tpl->escape($milestoneRow->headline) . "</option>";
                                    } ?>
                                </select>
                                <input type="hidden" name="type" value="milestone"/>
                                <input type="hidden" name="leancanvasitemid" value="<?php echo $id; ?> "/>
                                <input type="button" value="<?php echo $tpl->__("buttons.save")?>" onclick="jQuery('#primaryCanvasSubmitButton').click()"
                                       class="btn btn-primary"/>
                                <a href="javascript:void(0);"
                                   onclick="leantime.ideasController.toggleMilestoneSelectors('hide');">
                                    <i class="fas fa-times"></i> <?php echo $tpl->__("links.cancel")?>
                                </a>
                            </div>
                        </div>
                    </li>
                    <?php
                } else {
                    if ($canvasItem['milestoneEditTo'] == "0000-00-00 00:00:00") {
                        $date = $tpl->__("text.no_date_defined");
                    } else {
                        $date = new DateTime($canvasItem['milestoneEditTo']);
                        $date = $date->format($tpl->__("language.dateformat"));
                    }

                    ?>

                    <li class="ui-state-default" id="milestone_<?php echo $canvasItem['milestoneId']; ?>"
                        class="leanCanvasMilestone">
                        <div class="ticketBox fixed">

                            <div class="row">
                                <div class="col-md-8">
                                    <strong><a href="<?=BASE_URL ?>/tickets/showKanban?milestone=<?php echo $canvasItem['milestoneId']; ?>"><?php $tpl->e($canvasItem['milestoneHeadline']); ?></a></strong>
                                </div>
                                <div class="col-md-4 align-right">
                                    <a href="<?=BASE_URL ?>/ideas/ideaDialog/<?php echo $id; ?>&removeMilestone=<?php echo $canvasItem['milestoneId']; ?>"
                                       class="ideaModal delete"><i class="fa fa-close"></i> <?php echo $tpl->__("links.remove")?></a>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-7">
                                    <?php echo $tpl->__("label.due") ?>
                                    <?php echo $date; ?>
                                </div>
                                <div class="col-md-5" style="text-align:right">
                                    <?=sprintf($tpl->__("text.percent_complete"), $canvasItem['percentDone'])?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-success" role="progressbar"
                                             aria-valuenow="<?php echo $canvasItem['percentDone']; ?>" aria-valuemin="0"
                                             aria-valuemax="100"
                                             style="width: <?php echo $canvasItem['percentDone']; ?>%">
                                            <span class="sr-only"><?=sprintf($tpl->__("text.percent_complete"), $canvasItem['percentDone'])?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php } ?>

            </ul>

        <?php } ?>
    </div>




</div>


</form>


<div class="showDialogOnLoad" >









        <?php if ($id != '') { ?>
            <a href="<?=BASE_URL ?>/ideas/delCanvasItem/<?php echo $id; ?>" class="ideaModal delete right"><i
                        class="fa fa-trash"></i> <?php echo $tpl->__("links.delete") ?></a>
        <?php } ?>







</div>

<script type="text/javascript">
    jQuery(document).ready(function(){

        leantime.editorController.initComplexEditor();
        leantime.ticketsController.initTagsInput();

        <?php if (!$login::userIsAtLeast($roles::$editor)) { ?>
            leantime.authController.makeInputReadonly(".nyroModalCont");


        <?php } ?>

        <?php if ($login::userHasRole([$roles::$commenter])) { ?>
        leantime.commentsController.enableCommenterForms();
        <?php }?>


    })
</script>
