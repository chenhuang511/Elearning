<?php echo $header; ?>
<section class="wrap">
    <div class="row">
        <?php echo $messages; ?>
        <div class="course-section col-sm-12">
            <?php echo $course->fullname ?>
            <?php echo $course->shortname ?>
            <?php echo $course->summary ?>
            <?php echo $course->startdate ?>
            <?php echo $course->enddate ?>
            <?php echo $course->status ?>
        </div>

        <div class="user-enrol">
            <div class="teacher-section col-sm-6">
                <div class="title">
                    <h2>Giáo viên</h2>
                </div>
                <div>
                    <?php foreach ($usersenrol  as $user) : ?>
                        <span class="tag tag-primary"><?php echo $user->fullname ?></span>
                    <?php endforeach; ?>
                </div>
                <a href="<?php echo Uri::to('admin/courses/' . $course->id . '/enrol/teacher'); ?>" class="btn btn-primary" id="add-teacher">Thêm giáo viên</a>
            </div>
            <div class="student-section col-sm-6">
                <div class="title">
                    <h2>Học viên</h2>
                </div>
                <div>
                    <?php foreach ($studentsenrol  as $student) : ?>
                        <span class="tag tag-default"><?php echo $student->fullname ?></span>
                    <?php endforeach; ?>
                </div>
                <a href="<?php echo Uri::to('admin/courses/' . $course->id . '/enrol/student'); ?>" class="btn btn-primary" id="add-student">Thêm học viên</a>
            </div>
        </div>
    </div>
</section>
<?php echo $footer; ?>
