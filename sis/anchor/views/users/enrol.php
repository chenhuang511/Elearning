<?php echo $header; ?>
<ol class="breadcrumb">
    <li><a href="<?php echo Uri::to('admin'); ?>">Trang chủ</a></li>
    <li class="active">Ghi danh giáo viên</li>
</ol>
<section class="wrap">
    <?php echo $messages; ?>
    <input type="hidden" name="token" id="token" value="<?php echo Csrf::token(); ?>">
    <input type="hidden" name="courseid" id="courseid" value="<?php echo $course->id; ?>">
    <?php if ($pages->count): ?>
        <div class="clearfix">
            <form action="<?php echo Uri::to('admin/courses/' . $course->id . '/enrol/teacher/search') ?>" method="get" class="pull-right form-inline mb10">
                <div class="form-group">
                    <?php echo Form::text('key', Input::get('key'), array('class' => 'form-control', 'placeholder' => 'Tên sinh viên')); ?>
                </div>
                <?php echo Form::button( __('Tìm Kiếm'), array('type' => 'submit', 'class' => 'btn btn-primary')); ?>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover adm-table">
                <thead>
                <tr>
                    <th>Mã</th>
                    <th>Họ và tên</th>
                    <th>Email</th>
                    <th>Chứ vụ</th>
                    <th>Quản lý</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($pages->results as $item): $display_pages = array($item); ?>
                    <?php foreach ($display_pages as $page) : ?>
                        <tr>
                            <td><?php echo $page->id ?></td>
                            <td>
                                <a href="<?php echo Uri::to('admin/curriculum/update/course/' . $page->id); ?>">
                                <span class="bhxh-course">
                                    <?php echo $page->real_name; ?>
                                </span>
                                </a>
                            </td>
                            <td>
                                <?php echo $page->email; ?>
                            </td>
                            <td>
                                abc
                            </td>
                            <td>
                                 <button data-userid="<?php echo $page->id ?>" id="enrol-user" class="btn btn-primary">
                                     Ghi danh
                                 </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <aside class="paging"><?php echo $pages->links(); ?></aside>

    <?php else: ?>
        <aside class="empty pages">
            <span class="icon"></span>
            <?php echo __('pages.nopages_desc'); ?><br>
        </aside>
    <?php endif; ?>
</section>
<script type="text/javascript">
    var addEnrolUser = (function ($) {
        var callAjax = function(url, token, userid) {
            $.ajax({
                method: "POST",
                url: url,
                data : { token: token, userid: userid },
                dataType: "text",
                success: function(result){

                    $('#load').removeClass();
                    $('#load').addClass('fa fa-check');
                }
            });
        }
        var init = function (url) {
            $('#enrol-user').click(function () {
                // call ajax
                token = $('#token').val();
                userid = $(this).data('userid');
                console.log(userid);
                $(this).append('<i id="load" class="fa fa-spinner fa-pulse fa fa-fw"></i>');
                callAjax(url, token, userid);
            });
        }
        return {
            init: init
        }
    }(jQuery));
    addEnrolUser.init('<?php echo base_url('admin/courses/' . $course->id . '/enrol/teacher') ?>');
</script>
<?php echo $footer; ?>
