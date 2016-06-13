<div class="panel-group" id="section-menu" role="tablist" aria-multiselectable="true">
    <?php
    global $CFG; ?>
    <div class="panel panel-default">
        <div class="panel-heading" role="tab">
            <h4 class="panel-title">
                <a id="csec-0" role="button" data-toggle="collapse" data-parent="#section-menu"
                   href="#collapseCourseSummary"
                   aria-expanded="true" aria-controls="collapseCourseSummary"
                   class="">
                    <i class="fa fa-caret-down" aria-hidden="true"></i> Giới thiệu </a>
            </h4>
        </div>
        <div id="collapseCourseSummary" class="panel-collapse collapse in" role="tabpanel"
             aria-labelledby="course-summary"
             aria-expanded="true">
            <div class="panel-body">
                <a id="course-summary" class="sublink" href="#tongquan">
                    <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                    Khóa học
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
                        <a id="csec-<?php echo $section->id ?>" role="button" data-toggle="collapse"
                           data-parent="#section-menu"
                           href="#<?php echo $collapse ?>"
                           aria-expanded="false" aria-controls="<?php echo $collapse ?>"
                           class="collapsed'" data-summary="<?php echo htmlspecialchars($section->summary) ?>">
                            <i class="fa fa-caret-right" aria-hidden="true"></i> <?php echo $section->name ?> </a>
                    </h4>
                </div>
                <div id="<?php echo $collapse ?>" class="panel-collapse collapse" role="tabpanel"
                     aria-labelledby="<?php echo $heading ?>"
                     aria-expanded="false">
                    <div class="panel-body">
                        <?php foreach ($section->modules as $keymod => $module) {
                            if ($module->modname !== 'forum' && $module->modname !== 'wiki') {
                                if ($module->modname === 'label') {
                                    ?>
                                    <a id="mlabel-<?php echo $module->id ?>" class="sublink"
                                       href="#mlabel-<?php echo $module->id ?>"
                                       data-description="<?php echo htmlspecialchars($module->description) ?>">
                                        <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                                        <?php echo $module->name ?>
                                    </a>
                                    <?php
                                } else { ?>
                                    <a class="sublink get-remote-content"
                                       data-module='<?php echo json_encode(array('url' => $CFG->wwwroot . '/mod/' . $module->modname . '/remote/api-view.php', 'params' => array('id' => $module->id), 'method' => 'get')); ?>'
                                       href="#">
                                        <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                                        <?php echo $module->name; ?>
                                    </a>
                                <?php } ?>
                            <?php }
                        } ?>
                    </div>
                </div>
            </div>
        <?php }
    } ?>
</div>
