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
 * Implements hook_civicrm_build().
 */
function sms_civicrm_buildForm($formName, &$form) {
  if ('CRM_SMS_Form_Provider' == $formName && !($form->_action & CRM_Core_Action::DELETE)) {
    if ('org.civicrm.sms.twilio' != CRM_Utils_Array::value('name', $form->_defaultValues)) {
      return NULL;
    }
    $id = $form->getVar('_id');
    $form->addElement('checkbox', 'is_auto_reply', ts('Autoreply to inbound SMS messages?'));
    $form->add('textarea', 'auto_reply_message', ts('Content of Autoreply to send'),
      "cols=50 rows=6"
    );

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
 */
function sms_civicrm_validateForm($formName, &$fields, &$files, &$form, &$errors) {
  if ('CRM_SMS_Form_Provider' == $formName && !($form->_action & CRM_Core_Action::DELETE)) {
    if ($fields['name'] != 'org.civicrm.sms.twilio') {
      return FALSE;
    }
    $fields['auto_reply_message'] = trim($fields['auto_reply_message']);
    if (!empty($fields['is_auto_reply']) && empty($fields['auto_reply_message'])) {
      $errors['auto_reply_message'] = ts('Content of Autoreply to send is a required field.');
    }
  }
}

/**
 * Implements hook_civicrm_postProcess().
 *
 */
function sms_civicrm_postProcess($formName, &$form) {
  if ('CRM_SMS_Form_Provider' == $formName && !($form->_action & CRM_Core_Action::DELETE)) {
    $submitValues = $form->_submitValues;
    if ($submitValues['name'] != 'org.civicrm.sms.twilio') {
      return FALSE;
    }
    if (!empty($submitValues['is_auto_reply'])) {
      $params = array(
        'is_auto_reply' => $submitValues['is_auto_reply'],
        'auto_reply_message' => $submitValues['auto_reply_message'],
      );
      $id = $form->getVar('_id');
      if (!$id) {
        $id = civicrm_api3('SmsProvider', 'getvalue', array_merge(
          array(
            'return' => 'id',
            'options' => array('limit' => 1, 'sort' => 'id DESC'),
          ), $submitValues)
        );
      }
      CRM_Core_BAO_Setting::setItem($params, CRM_Core_BAO_Setting::SYSTEM_PREFERENCES_NAME, 'twilio_reply_' . $id);
    }
  }
}

/**
 * Implements hook_civicrm_post().
 *
 */
function sms_civicrm_post($op, $objectName, $objectId, &$objectRef) {
  if ($objectName == 'Activity' && $op == 'create') {

    $activityTypeID = civicrm_api3('OptionValue', 'getvalue', array(
      'return' => 'value',
      'option_group_id' => 'activity_type',
      'name' => 'Inbound SMS',
    ));
    if ($objectRef->activity_type_id != $activityTypeID) {
      return FALSE;
    }

    $phoneNumber = CRM_Utils_Request::retrieve('From', 'String');
    $toNumber = CRM_Utils_Request::retrieve('To', 'String');
    $accountSid = CRM_Utils_Request::retrieve('AccountSid', 'String');
    $provider = CRM_Utils_Request::retrieve('provider', 'String');
    $smsProviders = civicrm_api3('SmsProvider', 'get', array(
      'name' => $provider,
      'username' => $accountSid,
      'is_active' => 1,
    ));
    $userID = $providerID = $smsText = NULL;
    foreach ($smsProviders['values'] as $values) {
      $id = $values['id'];
      $settings = CRM_Core_BAO_Setting::getItem(CRM_Core_BAO_Setting::SYSTEM_PREFERENCES_NAME, 'twilio_reply_' . $id);
      if (!empty($settings) && !empty($settings['is_auto_reply'])) {
        $providerID = $id;
        $smsText = $settings['auto_reply_message'];
        break;
      }
    }
    if ($providerID == NULL || $smsText == NULL) {
      return FALSE;
    }
    $returnProperties = array(
      'sort_name' => 1,
      'phone' => 1,
      'do_not_sms' => 1,
      'is_deceased' => 1,
      'display_name' => 1,
    );
    $activityContacts = civicrm_api3('ActivityContact', 'get', array('activity_id' => $objectRef->id));

    foreach ($activityContacts['values'] as $values) {
      if ($values['record_type_id'] == CRM_Core_PseudoConstant::getKey('CRM_Activity_DAO_ActivityContact', 'record_type_id', 'Activity Targets')) {
        $contactIds[] = $values['contact_id'];
      }
      elseif ($values['record_type_id'] == CRM_Core_PseudoConstant::getKey('CRM_Activity_DAO_ActivityContact', 'record_type_id', 'Activity Source ')) {
        $userID = $values['contact_id'];
      }
    }
    list($contactDetails) = CRM_Utils_Token::getTokenDetails($contactIds,
      $returnProperties,
      FALSE,
      FALSE
    );
    $formattedContactDetails[] = reset($contactDetails);
    $thisValues = $smsParams = array(
      'activity_subject' => ts('Inbound auto-reply SMS'),
      'sms_provider_id' => $providerID,
      'sms_text_message' => $smsText,
      'SMSsaveTemplateName' => NULL,
      'provider_id' => $providerID,
    );
    list($sent, $activityId, $countSuccess) = CRM_Activity_BAO_Activity::sendSMS($formattedContactDetails,
      $thisValues,
      $smsParams,
      $contactIds,
      $userID
    );
  }
}
