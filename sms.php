<?php

require_once 'sms.civix.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function sms_civicrm_config(&$config) {
  _sms_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @param array $files
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function sms_civicrm_xmlMenu(&$files) {
  _sms_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function sms_civicrm_install() {
  _sms_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function sms_civicrm_uninstall() {
  _sms_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function sms_civicrm_enable() {
  _sms_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function sms_civicrm_disable() {
  _sms_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed
 *   Based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function sms_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _sms_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function sms_civicrm_managed(&$entities) {
  _sms_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * @param array $caseTypes
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function sms_civicrm_caseTypes(&$caseTypes) {
  _sms_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function sms_civicrm_angularModules(&$angularModules) {
_sms_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function sms_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _sms_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Functions below this ship commented out. Uncomment as required.
 *

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function sms_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
function sms_civicrm_navigationMenu(&$menu) {
  _sms_civix_insert_navigation_menu($menu, NULL, array(
    'label' => ts('The Page', array('domain' => 'biz.jmaconsulting.autoreply.twilo.sms')),
    'name' => 'the_page',
    'url' => 'civicrm/the-page',
    'permission' => 'access CiviReport,access CiviContribute',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _sms_civix_navigationMenu($menu);
} // */

/**
 * Implements hook_civicrm_buildForm().
 *
 *
 * @param string $formName
 * @param CRM_Core_Form $form
 */
function sms_civicrm_buildForm($formName, &$form) {
  if ('CRM_SMS_Form_Provider' == $formName) {
    $id = $form->getVar('_id');
    $form->addElement('checkbox', 'is_auto_reply', ts('Autoreply to inbound SMS messages?'));
    $form->add('textarea', 'auto_reply_message', ts('Content of Autoreply to send'),
      "cols=50 rows=6"
    );
    // FIXME : fix tpl for only twilo reply
    CRM_Core_Region::instance('page-body')->add(array(
      'template' => 'CRM/SMS/Form/Provider.extra.tpl',
    ));
    if ($id) {
      $defaults = CRM_Core_BAO_Setting::getItem(CRM_Core_BAO_Setting::SYSTEM_PREFERENCES_NAME, 'twilio_reply_' . $id);
      $form->setDefaults($defaults);
    }
  }
}

/**
 * Implements hook_civicrm_validateForm().
 *
 * @param string $formName
 * @param array $fields
 * @param array $files
 * @param CRM_Core_Form $form
 * @param array $errors
 */
function sms_civicrm_validateForm($formName, &$fields, &$files, &$form, &$errors) {
  if ('CRM_SMS_Form_Provider' == $formName) {
    if ($fields['name'] != 'org.civicrm.sms.twilio') {
      return FALSE;
    }
    $fields['auto_reply_message'] = trim($fields['auto_reply_message'] );
    if (!empty($fields['is_auto_reply']) && empty($fields['auto_reply_message'])) {
      $errors['auto_reply_message'] = ts('Content of Autoreply to send is a required field.');
    }
  }
}

/**
 * Implements hook_civicrm_postProcess().
 *
 * @param string $formName
 * @param CRM_Core_Form $form
 */
function sms_civicrm_postProcess($formName, &$form) {
  if ('CRM_SMS_Form_Provider' == $formName) {
    $submitValues = $form->_submitValues;
    if ($submitValues['name'] != 'org.civicrm.sms.twilio') {
      return FALSE;
    }
    if (array_key_exists('is_auto_reply', $submitValues)) {
      $params = array(
        'is_auto_reply' => $submitValues['is_auto_reply'],
        'auto_reply_message' => $submitValues['auto_reply_message'],
      );
      // FIXME: incase of new form submission
      $id = $form->getVar('_id');
      CRM_Core_BAO_Setting::setItem($params, CRM_Core_BAO_Setting::SYSTEM_PREFERENCES_NAME, 'twilio_reply_' . $id);
    }
  }
}