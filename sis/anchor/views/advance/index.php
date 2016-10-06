<?php echo $header; ?>
<ol class="breadcrumb">
    <li><a href="<?php echo Uri::to('admin'); ?>">Trang chủ</a></li>
    <li class="active">Tạm ứng tiền</li>
</ol>
<section class="wrap">
    <p class="text-right">
        <?php echo Html::link('admin/advance/course/' . $courseId . '/add', __('advance.create_advance'), array('class' => 'btn btn-primary')); ?>
    </p>
    <?php echo $messages; ?>

    <div class="row">
        <div class="statusbar col-sm-3">
            <div class="sidebarbox">
                <h5>Tìm kiếm</h5>
                <form action="<?php echo Uri::to('admin/advance/course/' . $courseId . '/search'); ?>" method="GET"
                      class="adm-search-box">
                    <div class="form-group">
                        <label for="key_id" class="control-label">Mã tạm ứng</label>
                        <?php echo Form::text('key_id', Input::get('key'), array('class' => 'form-control', 'placeholder' => 'Mã tạm ứng', 'id' => 'key_id')); ?>
                    </div>
                    <div class="form-group">
                        <label for="key_name" class="control-label">Người yêu cầu</label>
                        <?php echo Form::text('key_name', Input::get('key'), array('class' => 'form-control', 'placeholder' => 'Người yêu cầu', 'id' => 'key_name')); ?>
                    </div>
                    <div class="form-group">
                        <label for="gradeMin">Số tiền (thấp nhất)</label>
                        <?php echo Form::text('moneyMin', Input::get('gradeMin'), array('class' => 'form-control', 'id' => 'moneyMin')); ?>
                        <input type="hidden" id="hidden_moneyMin" value="">
                        <label for="gradeMax">Số tiền (cao nhất)</label>
                        <?php echo Form::text('moneyMax', Input::get('gradeMax'), array('class' => 'form-control', 'id' => 'moneyMax')); ?>
                        <input type="hidden" id="hidden_moneyMax" value="">
                    </div>
                    <div class="form-group text-right">
                        <?php echo Form::button(__('Tìm kiếm'), array('type' => 'submit', 'class' => 'btn btn-primary', 'id' => 'search_')); ?>
                    </div>
                </form>
            </div>
            <div class="sidebarbox">
                <h5>Trạng thái</h5>
                <ul class="sidebar statuses">
                    <?php foreach ($statuses as $data):
                    extract($data); ?>
                    <li>
                        <?php echo Html::link('admin/advance/course/' . $courseId . '' . $url, '<span class="icon"></span> ' . __($lang), array(
                            'class' => (isset($status) && $status == $url ? 'active ' : '') . $class
                        )); ?>
                        <?php endforeach; ?>
                    </li>
                </ul>
            </div>
        </div>
        <div class="list_advance col-sm-9">
            <?php if ($advance->results): ?>
            <div class="table-responsive">
                <table class="sort-table table table-hover" id="mytable">
                    <thead>
                    <tr>
                        <th>Mã</th>
                        <th>Người yêu cầu</th>
                        <th>Số tiền (VNĐ)</th>
                        <th>Trạng thái</th>
                        <th>Ngày yêu cầu</th>
                        <th>Ngày xét duyệt</th>
                        <td></td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($advance->results as $item): $display_pages = array($item); ?>
                        <?php foreach ($display_pages as $page) : ?>
                            <tr>

                                <td><?php echo $page->id; ?></td>

                                <td><?php echo $page->user; ?></td>

                                <td class="currency-format"><?php echo number_format($page->money); ?></td>

                                <td><em><?php echo __('advance.' . $page->status); ?></em></td>

                                <td><?php echo $page->time_request; ?></td>
                                <td>
                                    <?php
                                    if (isset($page->time_response) && $page->time_response !== '0000-00-00')
                                        echo $page->time_response;
                                    ?>
                                </td>
                                <td>
                                    <a href="<?php echo Uri::to('admin/advance/course/' . $courseId . '/edit/' . $page->data['id']); ?>"
                                    >Sửa<i class="fa fa-pencil" aria-hidden="true"></i></a> |
                                    <a href="<?php echo Uri::to('admin/advance/course/' . $courseId . '/delete/' . $page->data['id']); ?>"
                                    >Xóa<i class="fa fa-times" aria-hidden="true"></i></a></a>
                                </td>
                            </tr>

                        <?php endforeach; ?>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <aside class="paging"><?php echo $advance->links(); ?></aside>
        </div>
    </div>

<?php else: ?>
    <p class="empty posts">
        <span class="icon"></span>
        <?php echo __('posts.noposts_desc'); ?><br>
    </p>

<?php endif; ?>
</section>
<script src="<?php echo asset_url('js/jquery.tablesorter.min.js'); ?>"></script>
<script src="<?php echo asset_url('js/accounting.min.js'); ?>"></script>
<script src="<?php echo asset_url('js/currency-module.js'); ?>"></script>
<script type="text/javascript">
    (function($){
        $(document).ready(function () {
            $('#mytable').tablesorter();

            var inputs = ['#moneyMin', '#moneyMax'];
            var hiddens = ['#hidden_moneyMin', '#hidden_moneyMax'];

            currencyModule.init(accounting, inputs, hiddens);

        });
    })(jQuery);

</script>
<?php echo $footer; ?>