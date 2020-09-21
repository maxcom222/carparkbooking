<?php
if(isset($_GET['hide']) && (int)$_GET['hide'] == 0)
{
	if (isset($tpl['locale_arr']) && is_array($tpl['locale_arr']) && !empty($tpl['locale_arr']) && count($tpl['locale_arr']) > 1)
	{
		$locale_id = $controller->pjActionGetLocale();
		$selected_title = null;
		$selected_src = NULL;
		
		foreach ($tpl['locale_arr'] as $locale)
		{
			if ($locale_id == $locale['id'])
			{
				$selected_title = $locale['language_iso'];
				$lang_iso = explode("-", $selected_title);
				if(isset($lang_iso[1]))
				{
					$selected_title = $lang_iso[1];
				}
				if (!empty($locale['flag']) && is_file(PJ_INSTALL_PATH . $locale['flag']))
				{
					$selected_src = PJ_INSTALL_FOLDER . $locale['flag'];
				} elseif (!empty($locale['file']) && is_file(PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $locale['file'])) {
					$selected_src = PJ_INSTALL_FOLDER . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $locale['file'];
				}
				break;
			}
		}
		?>
		<button type="button" class="btn btn-default dropdown-toggle pjCpbBtn pjCpbBtnLanguage" data-toggle="dropdown" aria-expanded="false">
			<img src="<?php echo $selected_src;?>" alt="" />

			<?php echo mb_strtoupper($selected_title, 'UTF-8');?>

			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu pjCpbLanguageOptions">
			<?php
			foreach ($tpl['locale_arr'] as $k => $locale)
			{
				$selected_src = NULL;
				if (!empty($locale['flag']) && is_file(PJ_INSTALL_PATH . $locale['flag']))
				{
					$selected_src = PJ_INSTALL_FOLDER . $locale['flag'];
				} elseif (!empty($locale['file']) && is_file(PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $locale['file'])) {
					$selected_src = PJ_INSTALL_FOLDER . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $locale['file'];
				}
				?>
				<li>
					<a href="#" class="pjCpbBtn cpSelectorLocale<?php echo $locale_id == $locale['id'] ? ' pjCpbBtnActive' : null;?>" data-id="<?php echo $locale['id'];?>">
						<img src="<?php echo $selected_src;?>" alt="" />
	
						<?php echo pjSanitize::html($locale['title']); ?>
					</a>
				</li>
				<?php
			}
			?>
		</ul>
		<?php
	}
} 
?>