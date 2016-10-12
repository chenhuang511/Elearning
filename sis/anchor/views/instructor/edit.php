<?php echo $header; ?>

<ol class="breadcrumb">
	<li><a href="<?php echo Uri::to('admin'); ?>">Trang chủ</a></li>
	<li><a href="<?php echo Uri::to('admin/instructor'); ?>">Quản lý giảng viên</a></li>
	<li class="active">Sửa thông tin</li>
</ol>

<hgroup class="wrap">
	<h1 style="margin: 0;"><?php echo 'Sửa thông tin' ?></h1>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<?php if(Auth::admin()) : ?>
	<form method="post" action="<?php echo Uri::to('admin/instructor/edit/' . $instructor->id); ?>" novalidate autocomplete="off" enctype="multipart/form-data">

		<input name="token" type="hidden" value="<?php echo $token; ?>">
		
		<fieldset class="half split">
			<p>
				<label for="label-fullname"><?php echo __('instructor.fullname'); ?>:</label>
				<?php echo Form::text('fullname', Input::previous('fullname', $instructor->fullname), array('id' => 'label-fullname')); ?>
			</p>
			<p>
				<label for="label-email"><?php echo __('instructor.email'); ?>:</label>
				<?php echo Form::text('email', Input::previous('email', $instructor->email), array('id' => 'label-email')); ?>
			</p>
			<p>
				<label for="label-birthday"><?php echo __('instructor.birthday'); ?>:</label>
				<?php echo Form::date('birthday', Input::previous('birthday', $instructor->birthday), array('id' => 'label-birthday')); ?>
			</p>
			<p>
				<label for="label-subject"><?php echo __('instructor.subject'); ?>:</label>
				<?php echo Form::text('subject', Input::previous('subject', $instructor->subject), array('id' => 'label-subject')); ?>
			</p>
			<p style="height: 39px"></p>
			<p style="height: 39px"></p>
			<aside class="buttons" style="padding-left: 5px">
				<?php echo Form::button(__('global.update'), array(
					'class' => 'btn btn-primary',
					'type' => 'submit'
				)); ?>

				<?php echo Html::link('admin/instructor' , __('global.cancel'), array('class' => 'btn btn-primary')); ?>
			</aside>
		</fieldset>

		<fieldset class="half split">
		<?php foreach($contracts as $contract): 
		$id = $contract->id; ?>
		<div style="border:1px solid;border-color:blue">
			<p>
				<label for="label-name_contract"><?php echo __('contract.name_contract'); ?>:</label>
				<?php echo Form::text('name_contract'. $id, Input::previous('name_contract', $contract->name_contract), array('id' => 'label-name_contract')); ?>
			</p>
			<p>
				<label><?php echo __('contract.type'); ?>:</label>
				<?php echo Form::select('type'. $id, $type, Input::previous('type', $contract->type), array('class' => 'type')); ?>
			</p>
			<div class="organization_form" id="organization_form">
				<p>
					<label for="label-name_partner"><?php echo __('contract.name_partner'); ?>:</label>
					<?php echo Form::text('name_partner'. $id, Input::previous('name_partner', $contract->name_partner), array('id' => 'label-name_partner')); ?>
				</p>
				<p>
					<label for="label-name_head"><?php echo __('contract.name_head'); ?>:</label>
					<?php echo Form::text('name_head'. $id, Input::previous('name_head', $contract->name_head), array('id' => 'label-name_head')); ?>
				</p>
				<p>
					<label for="label-tax_code"><?php echo __('contract.tax_code'); ?>:</label>
					<?php echo Form::text('tax_code'. $id, Input::previous('tax_code', $contract->tax_code), array('id' => 'label-tax_code')); ?>
				</p>
				<p>
					<label for="label-number_phone"><?php echo __('contract.number_phone'); ?>:</label>
					<?php echo Form::text('number_phone'. $id, Input::previous('number_phone', $contract->number_phone), array('id' => 'label-number_phone')); ?>
				</p>
				<p>
					<label for="label-address"><?php echo __('contract.address'); ?>:</label>
					<?php echo Form::text('address'. $id, Input::previous('address', $contract->address), array('id' => 'label-address')); ?>
				</p>
			</div>
			<p>
				<label for="label-start_date"><?php echo __('contract.start_date'); ?>:</label>
				<?php echo Form::date('start_date'. $id, Input::previous('start_date', $contract->start_date), array('id' => 'label-start_date')); ?>
			</p>
			<p>
				<label for="label-end_date"><?php echo __('contract.end_date'); ?>:</label>
				<?php echo Form::date('end_date'. $id, Input::previous('end_date', $contract->end_date), array('id' => 'label-end_date')); ?>
			</p>
			<p>
				<label for="label-salary"><?php echo __('contract.salary'); ?>:</label>
				<?php echo Form::text('salary'. $id, Input::previous('salary', $contract->salary), array('id' => 'label-salary')); ?>
			</p>
			<p>
				<label for="label-state"><?php echo 'Trạng thái' ?>:</label>
				<?php echo Form::select('state'. $id, $state, Input::previous('state', $contract->state), array('id' => 'label-state')); ?>
			</p>
			<p>
				<label for="label-rules"><?php echo __('contract.rules'); ?>:</label>
				<?php echo Form::textarea('rules'. $id, Input::previous('rules', $contract->rules), array('cols' => 20 ,'id' => 'label-rules')); ?>
			</p>
			</div></br></br>
		<?php endforeach; ?>
		</fieldset>
	</form>
	<?php else : ?>
		<p>You do not have the required privileges to modify this instructor information, you must be an Administrator. Please contact the Administrator of the site if you are supposed to have these privileges.</p>
		<br><a class="btn" href="<?php echo Uri::to('admin/instructor'); ?>">Go back</a>
	<?php endif; ?>
	<input id="menuSelected" type="hidden" value="<?php if (isset($tab)): echo $tab; endif; ?>">
</section>
<script>
  var list = document.getElementsByClassName("organization_form");
  var list_type = document.getElementsByClassName("type");
  for (var i = 0; i < list.length; i++) {
	list_type[i].setAttribute("id", "t" + i);
	list[i].setAttribute("id", "form_t" + i);
  }
  $(document).ready(function() {
	for (var i = 0; i < list.length; i++) {
		if($('#t'+ i).val() == "personal"){
			document.getElementById("form_t"+i).setAttribute("style","display:none;");
		}
		else{
			document.getElementById("form_t"+i).setAttribute("style","display:inline;");
		}
	}
   });
   for (var i = 0; i < list.length; ++i) {
	console.log($('#t'+ i));
  	$('#t'+ i).on('change', function(){
		var $this = $(this);
		var $value = $this.val();
		if($value == "personal"){
			document.getElementById("form_" + $this[0].id).setAttribute("style","display:none;");
		}
		else{
			document.getElementById("form_"+ $this[0].id).setAttribute("style","display:inline;");
		}
	});
   }
 </script>
<script src="<?php echo asset('anchor/views/assets/js/upload-fields.js'); ?>"></script>

<?php echo $footer; ?>
