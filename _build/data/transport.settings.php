<?php

$settings = array();

$tmp = array(
  'refresh_on_page_save' => array(
    'value' => '1',
    'xtype' => 'combo-boolean',
    'area' => 'lmims_area'
  ),
  'remove_related_lmims_on_empty_trash' => array(
    'value' => '1',
    'xtype' => 'combo-boolean',
    'area' => 'lmims_area'
  )
);

$tmp['core_path'] = array(
  'xtype' => 'textfield',
  'value' => '',
  'area' => 'lmims.paths',
);

if (PKG_DEV) {
  $tmp['core_path']['value'] = '{base_path}'. PKG_NAME .'/core/components/'. PKG_NAME_LOWER .'/';
} else {
  $tmp['core_path']['value'] = '';
}

foreach ($tmp as $k => $v) {
	/* @var modSystemSetting $setting */
	$setting = $modx->newObject('modSystemSetting');
	$setting->fromArray(array_merge(
		array(
			'key' => PKG_NAME_LOWER .'.' . $k,
			'namespace' => PKG_NAME_LOWER
		), $v
	), '', true, true);

	$settings[] = $setting;
}

unset($tmp);
return $settings;
