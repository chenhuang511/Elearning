<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('instructor.editing_user'); ?></h1>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<?php if(Auth::admin()) : ?>
	<form method="post" action="<?php echo Uri::to('admin/instructor/edit/' . $instructor->id); ?>" novalidate autocomplete="off" enctype="multipart/form-data">

		<input name="token" type="hidden" value="<?php echo $token; ?>">
		
		<fieldset class="half split">
		
			<p>
				<label for="label-firstname"><?php echo __('instructor.first_name'); ?>:</label>
				<?php echo Form::text('firstname', Input::previous('firstname', $instructor->firstname), array('id' => 'label-firstname')); ?>
			</p>
			<p>
				<label for="label-lastname"><?php echo __('instructor.last_name'); ?>:</label>
				<?php echo Form::text('lastname', Input::previous('lastname', $instructor->lastname), array('id' => 'label-lastname')); ?>
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
		
		</fieldset>

		<fieldset class="half split">
		<?php foreach($contract as $contract): ?>
		<div style="border:1px solid;border-color:blue">
			<p>
				<label for="label-name_contract"><?php echo __('contract.name_contract'); ?>:</label>
				<?php echo Form::text('name_contract', Input::previous('name_contract', $contract->name_contract), array('id' => 'label-name_contract')); ?>
			</p>
			<p>
				<label for="label-type"><?php echo __('contract.type'); ?>:</label>
				<?php echo Form::select('type', $type, Input::previous('type', $contract->type), array('id' => 'label-type')); ?>
			</p>
			<p>
				<label for="label-name_partner"><?php echo __('contract.name_partner'); ?>:</label>
				<?php echo Form::text('name_partner', Input::previous('name_partner', $contract->name_partner), array('id' => 'label-name_partner')); ?>
			</p>
			<p>
				<label for="label-start_date"><?php echo __('contract.start_date'); ?>:</label>
				<?php echo Form::date('start_date', Input::previous('start_date', $contract->start_date), array('id' => 'label-start_date')); ?>
			</p>
			<p>
				<label for="label-end_date"><?php echo __('contract.end_date'); ?>:</label>
				<?php echo Form::date('end_date', Input::previous('end_date', $contract->end_date), array('id' => 'label-end_date')); ?>
			</p>
			<p>
				<label for="label-salary"><?php echo __('contract.salary'); ?>:</label>
				<?php echo Form::text('salary', Input::previous('salary', $contract->salary), array('id' => 'label-salary')); ?>
			</p>
			<p>
				<label for="label-state"><?php echo __('contract.state'); ?>:</label>
				<?php echo Form::select('state', $state, Input::previous('state', $contract->state), array('id' => 'label-state')); ?>
			</p>
			<p>
				<label for="label-rules"><?php echo __('contract.rules'); ?>:</label>
				<?php echo Form::textarea('rules', Input::previous('rules', $contract->rules), array('cols' => 20 ,'id' => 'label-rules')); ?>
			</p>			
		</div></br>
		<?php endforeach; ?>
		</fieldset>
		<aside class="buttons">
			<?php echo Form::button(__('global.update'), array(
				'class' => 'btn',
				'type' => 'submit'
			)); ?>

			<?php echo Html::link('admin/instructor' , __('global.cancel'), array('class' => 'btn cancel blue')); ?>
		</aside>
	</form>
	<?php else : ?>
		<p>You do not have the required privileges to modify this instructor information, you must be an Administrator. Please contact the Administrator of the site if you are supposed to have these privileges.</p>
		<br><a class="btn" href="<?php echo Uri::to('admin/instructor'); ?>">Go back</a>
	<?php endif; ?>
</section>

<script src="<?php echo asset('anchor/views/assets/js/upload-fields.js'); ?>"></script>

<?php echo $footer; ?>
