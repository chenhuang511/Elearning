<div class="content-wrap course-progress clearfix">
    <div class="progress-wrap">
        <h3 class="proress-title">
            Course progress for student.
        </h3>
        <div class="progress-graph" id="graph-container">
            <div class="chart-placeholder"></div>
        </div>
    </div>
    <div class="course-wrap clearfix">
        <?php
        foreach ($course as $key => $section):
        ?>
            <div class="course-section">
                <div class="row">
                    <div class="col-sm-6 col-md-4">
                        <h3 class="section-name">
                            <?php echo $section->name; ?>
                        </h3>
                    </div>
                    <div class="col-sm-6 col-md-8">
                        <div class="section-module-list">
                            <?php
                            foreach ($section->modules as $k => $module):
                            ?>
                                <div class="section-module">
                                    <h4 class="section-module-name">
                                        <?php echo $module->name; ?>
                                        <span class="section-module-name-complete">
                                            (<?php
                                            $complete = rand(0, 10);
                                            $total = rand($complete, 10);
                                            echo $complete . '/' . $total;
                                            ?>)
                                        </span>
                                    </h4>
                                    <p class="section-module-type">
                                        <?php echo ucfirst($module->modname); ?>
                                    </p>
                                    <p class="section-module-complete">
                                        Problem scores:
                                        <span class="module-score">
                                            <?php echo $complete . '/' . $total; ?>
                                        </span>
                                    </p>
                                </div>
                            <?php
                            endforeach;
                            ?>
                        </div>
                    </div>
                </div>
                <div class="course-dump">
                    <?php //var_dump($section->modules);
                    ?>
                </div>
            </div>
        <?php
        endforeach;
        ?>
    </div>
</div>
