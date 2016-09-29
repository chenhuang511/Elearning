<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('contract.contract'); ?></h1>

	<?php if(Auth::admin()) : ?>
	<nav>
		<?php echo Html::link('admin/contract/add', __('contract.create_contract'), array('class' => 'btn')); ?>
	</nav>
	<?php endif; ?>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<ul class="list">
		<?php foreach($contract->results as $contract): ?>
		<li>
			<a href="<?php echo Uri::to('admin/contract/edit/' . $contract->id); ?>">
				<strong><?php echo __($contract->name_partner) ; ?></strong>
				<span><?php echo __('contract.type'); ?>: <?php echo  __('contract.'. $contract->type); ?></span>

				<em class="highlight"><?php echo __($contract->lastname." ".$contract->firstname); ?></em>
			</a>
		</li>
		<?php endforeach; ?>
	</ul>
</section>

<?php echo $footer; ?>
