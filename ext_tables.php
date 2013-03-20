<?php 
if (!defined ('TYPO3_MODE')) die ('Access denied.');

Tx_Extbase_Utility_Extension::registerPlugin(
    $_EXTKEY,// The extension name (in UpperCamelCase) or the extension key (in lower_underscore)
    'Pi1',                // A unique name of the plugin in UpperCamelCase
    'RSS-Feed renderer'    // A title shown in the backend dropdown field
);

Tx_Extbase_Utility_Extension::registerPlugin(
    $_EXTKEY,// The extension name (in UpperCamelCase) or the extension key (in lower_underscore)
    'Pi2',                // A unique name of the plugin in UpperCamelCase
    'Json-Feed renderer'    // A title shown in the backend dropdown field
);

t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'fluidAjaxWidgetCachedResponse');

$extensionName = strtolower(t3lib_div::underscoredToUpperCamelCase($_EXTKEY));

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$extensionName . '_pi1'] = 'layout,select_key,recursive,pages';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$extensionName . '_pi1'] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue($extensionName . '_pi1', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_list.xml');

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$extensionName . '_pi2'] = 'layout,select_key,recursive,pages';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$extensionName . '_pi2'] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue($extensionName . '_pi2', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_list.xml');


if (TYPO3_MODE === 'BE') {
    Tx_Extbase_Utility_Extension::registerModule(
        $_EXTKEY,
        'tools',          // Main area
        'mod1',         // Name of the module
        '',             // Position of the module
        array(          // Allowed controller action combinations
            'OauthBackendAdmin' => 'index',
        ),
        array(          // Additional configuration
            'access'    => 'admin',
            'icon'      => 'EXT:'. $_EXTKEY . '/ext_icon.gif',
            'labels'    => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_mod.xml',
        )
    );
}
?>