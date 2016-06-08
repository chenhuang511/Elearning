<div class="panel-group" id="section-menu" role="tablist" aria-multiselectable="true">
    <?php
    global $CFG;
    foreach ($course as $key => $section) {
        $heading = 'mod-' . $section->id;
        $collapse = 'collapseMod' . $section->id;
        $expanded = $key > 0 ? 'false' : 'true';
        $classes = ' ';
        if ($expanded === 'true') {
            $classes = 'in';
        }

        if ($section->modules) {
            ?>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="<?php echo $heading ?>">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#section-menu" href="#<?php echo $collapse ?>"
                           aria-expanded="<?php echo $expanded ?>" aria-controls="<?php echo $collapse ?>"
                           class="<?php $expanded === 'true' ? '' : 'collapsed' ?>">
                            <i class="fa fa-caret-right" aria-hidden="true"></i> <?php echo $section->name ?> </a>
                    </h4>
                </div>
                <div id="<?php echo $collapse ?>" class="panel-collapse collapse <?php echo $classes ?>" role="tabpanel"
                     aria-labelledby="<?php echo $heading ?>"
                     aria-expanded="<?php echo $expanded ?>">
                    <div class="panel-body">
                        <?php foreach ($section->modules as $keymod => $module) {
                            if ($module->modname !== 'forum' && $module->modname !== 'label') {
                                ?>
                                <a class="sublink" href="<?php echo $CFG->wwwroot . '/mod/' . $module->modname . '/remote/view.php?id=' . $module->id ?>"><i class="fa fa-angle-double-right" aria-hidden="true"></i> <?php echo $module->name ?></a>
                            <?php }
                        } ?>
                    </div>
                </div>
            </div>
        <?php }
    } ?>
</div>