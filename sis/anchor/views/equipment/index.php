<?php echo $header; ?>
<section class="wrap">
    <?php echo $messages; ?>
    <?php if ($pages->count): ?>
        <p class="text-right">
            <a href="#" class="btn btn-success" id="add-remote-room">
                Tạo Thiết bị
            </a>
        </p>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Thời gian</th>
                <th>Tên chuyên đề</th>
                <th>Giảng viên thực hiện</th>
                <th>Ghi chú</th>
                <th>Thao tác</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($pages->results as $item): $display_pages = array($item); ?>
                <?php foreach ($display_pages as $page) : ?>
                    <tr>
                        <td><?php echo $page->virtual_class_equipmentday ?></td>
                        <td>
                                <span class="bhxh-course">
                                    <?php if ($page->virtual_class_equipmenttime !== NULL):
                                        echo '<strong>' . $page->virtual_class_equipmenttime . '</strong>' . ' ' . $page->virtual_class_equipmentname;
                                    else:
                                        echo $page->virtual_class_equipmentname;
                                    endif;
                                    ?>
                                </span>
                        </td>
                        <td><?php
                            echo $page->teacher_name;
                            ?></td>
                        <td>
                            <?php
                            echo $page->note;
                            ?>
                        </td>
                        <td>
                            <a href="<?php echo Uri::to('admin/curriculum/edit/virtual_class_equipment/' . $page->id); ?>"
                            >Sửa <i class="fa fa-pencil" aria-hidden="true"></i></a> |
                            <a href="<?php echo Uri::to('admin/curriculum/virtual_class_equipment/delete/' . $page->id); ?>"
                               onclick="return confirm('Bạn chắc chắn muốn xóa thông tin này');">Xóa <i class="fa fa-times"
                                                                                                 aria-hidden="true"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
            </tbody>
        </table>

        <aside class="paging"><?php echo $pages->links(); ?></aside>

    <?php else: ?>
        <aside class="empty pages">
            <span class="icon"></span>
            <?php echo __('pages.nopages_desc'); ?><br>
        </aside>
    <?php endif; ?>
</section>

<script type="text/javascript">

    var addRemoteRoom = (function () {
        var i = 0;
        var callAjax = function(url, token, roomid, loop) {
            $.ajax({
                method: "POST",
                url: url,
                data : { token: token, roomid: roomid, loop: loop },
                dataType: "text",
                success: function(result){
                    if(result == false && i < 100) {
                        callAjax(url, token, roomid, 1);
                        i++;
                    }

                    $('#load').removeClass();
                    if(result == false) {
                        $('#load').addClass('fa fa-exclamation-triangle');
                    }
                    $('#load').addClass('fa fa-check');
                }});
        }
        var init = function (url, token, roomid) {
            $('#add-remote-room').click(function () {
                // call ajax
                $(this).append('<i id="load" class="fa fa-spinner fa-pulse fa fa-fw"></i>');
                callAjax(url, token, roomid, 0);
            });
        }
        return {
            init: init
        }
    }());
    addRemoteRoom.init('<?php echo base_url('admin/curriculum/add/remote/room') ?>', '<?php echo Csrf::token(); ?>', '<?php echo $roomid ; ?>')
</script>
<?php echo $footer; ?>
