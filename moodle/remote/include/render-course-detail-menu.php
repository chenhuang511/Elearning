<div class="panel-group" id="section-menu" role="tablist" aria-multiselectable="true">
    <?php
    global $CFG; ?>
    <div class="panel panel-default">
        <div class="panel-heading" role="tab">
            <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#section-menu" href="#collapseCourseSummary"
                   aria-expanded="true" aria-controls="collapseCourseSummary"
                   class="">
                    <i class="fa fa-caret-right" aria-hidden="true"></i> Giới thiệu </a>
            </h4>
        </div>
        <div id="collapseCourseSummary" class="panel-collapse collapse in" role="tabpanel"
             aria-labelledby="course-summary"
             aria-expanded="true">
            <div class="panel-body">
                <a id="course-summary" class="sublink" href="">
                    <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                    Tổng quan
                </a>
            </div>
        </div>
    </div>
    <?php foreach ($course as $key => $section) {
        $heading = 'mod-' . $section->id;
        $collapse = 'collapseMod' . $section->id;
        ?>

        <?php if ($section->modules) {
            ?>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="<?php echo $heading ?>">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#section-menu"
                           href="#<?php echo $collapse ?>"
                           aria-expanded="false" aria-controls="<?php echo $collapse ?>"
                           class="collapsed'">
                            <i class="fa fa-caret-right" aria-hidden="true"></i> <?php echo $section->name ?> </a>
                    </h4>
                </div>
                <div id="<?php echo $collapse ?>" class="panel-collapse collapse" role="tabpanel"
                     aria-labelledby="<?php echo $heading ?>"
                     aria-expanded="false">
                    <div class="panel-body">
                        <?php foreach ($section->modules as $keymod => $module) {
                            if ($module->modname !== 'forum' && $module->modname !== 'label') {
                                ?>
                                <a class="sublink get-remote-content" data-module="<?php echo $module->modname; ?>"
                                   data-remote-id="<?php echo $module->id; ?>"
                                   href="#">
                                    <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                                    <?php echo $module->name ?>
                                </a>
                            <?php }
                        } ?>
                    </div>
                </div>
            </div>
        <?php }
    } ?>
</div>
