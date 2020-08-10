<?php
define('TEMPLATE_ID', 327);
define('SLOZOOEVENT', 1509);
define('SLOVAREVENT', 1510);
define('FUNDRAISING_TEMPLATE', 2606);

require_once 'aoeventtemplates.civix.php';

/**
 * Implementation of hook_civicrm_config
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function aoeventtemplates_civicrm_config(&$config) {
  _aoeventtemplates_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_xmlMenu
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function aoeventtemplates_civicrm_xmlMenu(&$files) {
  _aoeventtemplates_civix_civicrm_xmlMenu($files);
}

/**
 * Implementation of hook_civicrm_install
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function aoeventtemplates_civicrm_install() {
  _aoeventtemplates_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_uninstall
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function aoeventtemplates_civicrm_uninstall() {
  _aoeventtemplates_civix_civicrm_uninstall();
}

/**
 * Implementation of hook_civicrm_enable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function aoeventtemplates_civicrm_enable() {
  _aoeventtemplates_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function aoeventtemplates_civicrm_disable() {
  _aoeventtemplates_civix_civicrm_disable();
}

/**
 * Implementation of hook_civicrm_upgrade
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed  based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function aoeventtemplates_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _aoeventtemplates_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implementation of hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function aoeventtemplates_civicrm_managed(&$entities) {
  _aoeventtemplates_civix_civicrm_managed($entities);
}

/**
 * Implementation of hook_civicrm_caseTypes
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function aoeventtemplates_civicrm_caseTypes(&$caseTypes) {
  _aoeventtemplates_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implementation of hook_civicrm_alterSettingsFolders
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function aoeventtemplates_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _aoeventtemplates_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

function aoeventtemplates_civicrm_links($op, $objectName, $objectId, &$links, &$mask, &$values) {
  if (CRM_Core_Config::singleton()->userSystem->is_wordpress) {
    return;
  }
  if ($op == "event.manage.list") {
    $current_user = \Drupal::currentUser();
    $roles = $current_user->getRoles();
    if (!array_search('senior_staff', $roles) && !array_search('administrator', $roles)) {
      unset($links[3]);
    }
  }
}

function aoeventtemplates_civicrm_pageRun(&$page) {
  if (CRM_Core_Config::singleton()->userSystem->is_wordpress) {
    return;
  }
  if (get_class($page) == 'CRM_Event_Page_EventInfo') {
    $eventId = CRM_Core_Smarty::singleton()->get_template_vars('event')['id'];
    $templateId = civicrm_api3('Event', 'get', [
      'id' => $eventId,
      'return.custom_' . TEMPLATE_ID => 1,
    ])['values'][$eventId]['custom_' . TEMPLATE_ID];
    if ($templateId == SLOZOOEVENT || $templateId == SLOVAREVENT) {
      return;
    }
    $feeBlock = CRM_Core_Smarty::singleton()->get_template_vars('feeBlock');
    $feeBlock['isDisplayAmount'][9] = $feeBlock['isDisplayAmount'][10] = $feeBlock['isDisplayAmount'][11] = 0;
    CRM_Core_Smarty::singleton()->assign('feeBlock', $feeBlock);
    
    CRM_Core_Resources::singleton()->addScript(
      "CRM.$(function($) {
        $('table.fee_block-table tr:nth-child(2)').hide();
        $('table.fee_block-table tr:nth-child(3)').hide();
        $('table.fee_block-table tr:nth-child(4)').hide();
        $('table.fee_block-table tr:nth-child(5)').hide();
        $('table.fee_block-table tr:nth-child(7)').hide();
        $('table.fee_block-table tr:nth-child(8)').hide();
        $('#Event_Template__14').hide();
        $('#Event_Template__25').hide();
        $('#Event_Template__331 > div.crm-accordion-wrapper > div.crm-accordion-body > table:nth-child(1)').hide();
      });"
    );
  }
  if (get_class($page) == 'CRM_Admin_Page_EventTemplate') {
    $current_user = \Drupal::currentUser();
    $roles = $current_user->getRoles();
    if (!array_search('senior_staff', $roles) && !array_search('administrator', $roles)) {
      CRM_Core_Error::fatal(ts('You do not have permission to access this page.'));
    }
  }
  if (get_class($page) == "CRM_Contact_Page_View_Summary") {
    CRM_Core_Resources::singleton()->addScript(
      "CRM.$(function($) {
        $('#tab_custom_22').hide();
      });"
    ); 
  }
}

/**
 * Implementation of hook_civicrm_buildForm
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_buildForm
 */
function aoeventtemplates_civicrm_buildForm($formName, &$form) {
  if (CRM_Core_Config::singleton()->userSystem->is_wordpress) {
    return;
  }
  if (in_array($formName, ["CRM_Event_Form_Registration_Register", "CRM_Event_Form_ParticipantFeeSelection"])) {
    $templateId = civicrm_api3('Event', 'get', [
      'id' => $form->_eventId,
      'return.custom_' . TEMPLATE_ID => 1,
    ])['values'][$form->_eventId]['custom_' . TEMPLATE_ID];
    if ($templateId) {
      $template = getEventTemplates($templateId);
      $form->assign('currentTemplate', $template);
      CRM_Core_Region::instance('page-body')->add(array(
        'template' => 'CRM/AO/EventTemplate.tpl',
      ));
    }
  }
  if ($formName == "CRM_Event_Form_ManageEvent_EventInfo" && ($form->_action & CRM_Core_Action::ADD) && !$form->getVar('_isTemplate')) {
    $defaults = [
      'start_date' => date('m/d/Y'),
    ];
    $form->setDefaults($defaults);
  }

  // Set frozen fields.
  if ($formName == "CRM_Event_Form_ManageEvent_EventInfo" && !$form->getVar('_isTemplate')) {
    $current_user = \Drupal::currentUser();
    $roles = $current_user->getRoles();
    if (!array_search('administrator', $roles) && !array_search('senior_staff', $roles)) {
      $freezeElements = [
        'event_type_id',
        'default_role_id',
        'is_public',
        'is_share',
        'participant_listing_id',
      ];
      $form->freeze($freezeElements);
    }
    $form->addRule('start_date', ts('Please enter the event start date.'), 'required');
    $form->addRule('end_date', ts('Please enter the event end date'), 'required');
    //$form->addRule('start_date_time', ts('Please enter the event start time.'), 'required');
    //$form->addRule('end_date_time', ts('Please enter the event end time.'), 'required');
    CRM_Core_Resources::singleton()->addScript(
      "CRM.$(function($) {
        $( document ).ajaxComplete(function( event, xhr, settings ) {
          $('.custom_327_25-row').hide();
        });
      });"
    );
  }
  if ($formName == "CRM_Event_Form_ManageEvent_Location" && !$form->getVar('_isTemplate')) {
    $form->addRule('email[1][email]', ts('Please enter an email.'), 'required');
    $form->addRule('phone[1][phone]', ts('Please enter phone number.'), 'required');
  }
  if ($formName == "CRM_Event_Form_ManageEvent_Fee" && !$form->getVar('_isTemplate')) {
    $current_user = \Drupal::currentUser();
    $roles = $current_user->getRoles();
    if (!array_search('administrator', $roles) && !array_search('senior_staff', $roles)) {
      $freezeElements = [
        'is_monetary',
        'financial_type_id',
        'is_pay_later',
        'payment_processor',
        'fee_label',
      ];
      // Conditional price set freeze.
      $templateId = civicrm_api3('Event', 'get', [
        'id' => $form->_eventId,
        'return.custom_' . TEMPLATE_ID => 1,
      ])['values'][$form->_eventId]['custom_' . TEMPLATE_ID];
      if ($templateId) {
        $templateTitle = getEventTemplates($templateId);
        if ($templateTitle != "SLO Variable Pricing") {
          $freezeElements[] = 'price_set_id';
        }
      }
      $form->freeze($freezeElements);
    }
  }
  if ($formName == "CRM_Event_Form_ManageEvent_Registration" && !$form->getVar('_isTemplate')) {
    $current_user = \Drupal::currentUser();
    $roles = $current_user->getRoles();
    if ($form->_action & CRM_Core_Action::ADD) {
      $cid = CRM_Core_Session::singleton()->get('userID');
      if ($cid) {
        $details = CRM_Core_DAO::executeQuery("SELECT display_name, email FROM civicrm_contact c INNER JOIN civicrm_email e ON e.contact_id = c.id WHERE c.id = {$cid} AND e.is_primary = 1")->fetchAll()[0];
        if (!empty($details)) {
          $form->setDefaults(['confirm_from_name' => $details['display_name'], 'confirm_from_email' => $details['email']]);
        }
      }
    }
    if (!array_search('administrator', $roles) && !array_search('senior_staff', $roles)) {
      $freezeElements = [
        'registration_link_text',
        'is_multiple_registrations',
        'allow_same_participant_emails',
        'dedupe_rule_group_id',
        'expiration_time',
        'selfcancelxfer_time',
        'allow_selfcancelxfer',
        'confirm_title',
        'confirm_text',
        'confirm_footer_text',
        'thankyou_title',
        'thankyou_text',
        'thankyou_footer_text',
        'confirm_email_text',
        //'confirm_from_name',
        //'confirm_from_email',
      ];
      $form->freeze($freezeElements);
      CRM_Core_Resources::singleton()->addScript(
        "CRM.$(function($) {
           $('#registration_screen').find('table').next().hide();
           $('#is_online_registration').hide();
        });"
      );
    }
  }
  if ($formName == "CRM_Event_Form_ManageEvent_ScheduleReminders" && !$form->getVar('_isTemplate')) {
    $current_user = \Drupal::currentUser();
    $roles = $current_user->getRoles();
    if (!array_search('administrator', $roles) && !array_search('senior_staff', $roles)) {
      CRM_Core_Resources::singleton()->addScript(
        "CRM.$(function($) {
           $('.action-link').hide();
           $('.crm-scheduleReminders-is_active').next('td').hide();
        });"
      );
    }
  }
  if ($formName == "CRM_Friend_Form_Event" && !$form->getVar('_isTemplate')) {
    $current_user = \Drupal::currentUser();
    $roles = $current_user->getRoles();
    if (!array_search('administrator', $roles) && !array_search('senior_staff', $roles)) {
      $freezeElements = [
        'tf_is_active',
        'tf_title',
        'intro',
        'suggested_message',
        'general_link',
        'tf_thankyou_title',
        'tf_thankyou_text',
      ];
      $form->freeze($freezeElements);
    }
  }
  if ($formName == "CRM_PCP_Form_Event" && !$form->getVar('_isTemplate')) {
    $current_user = \Drupal::currentUser();
    $roles = $current_user->getRoles();
    if (!array_search('administrator', $roles) && !array_search('senior_staff', $roles)) {
      CRM_Core_Resources::singleton()->addScript(
        "CRM.$(function($) {
           $( document ).ajaxComplete(function( event, xhr, settings ) {
             $('#pcp_active').attr('disabled', true);
           });
        });"
      );
    }
  }
}

function aoeventtemplates_civicrm_validateForm($formName, &$fields, &$files, &$form, &$errors) {
  if ($formName == "CRM_Event_Form_Registration_Register") {
    if (!empty($form->_noFees)) {
      return TRUE;
    }
    $templateId = civicrm_api3('Event', 'get', [
      'id' => $form->_eventId,
      'return.custom_' . TEMPLATE_ID => 1,
    ])['values'][$form->_eventId]['custom_' . TEMPLATE_ID];
    if (!empty($templateId)) {
      $totalNumber = 0;
      $flag = TRUE;
      $priceSetId = CRM_Price_BAO_PriceSet::getFor('civicrm_event', $form->_eventId);
      $priceFieldIds = CRM_Core_DAO::executeQuery("SELECT id FROM civicrm_price_field WHERE price_set_id = %1", [1 => [$priceSetId, "Integer"]])->fetchAll();
      foreach ($priceFieldIds as $priceFids) {
        $priceFields[] = 'price_' . $priceFids['id'];
      }
      foreach ($priceFields as $price) {
        if (!empty($fields[$price])) {
          if ($templateId == SLOZOOEVENT) {
            // Check total number.
            $totalNumber += $fields[$price];
          }
          $flag = FALSE;
          break;
        }
      }
      if ($totalNumber > 4) {
        $errors['_qf_default'] = ts("Please select only upto 4 children with ASD.");
      }
      if (array_key_exists('price_192', $fields) && ($fields['price_192'] > $totalNumber)) {
        $errors['_qf_default'] = ts("Free Caregivers must be equal to or less than # or tickets for children with ASD.");
      }
      if ($flag) {
        $errors['_qf_default'] = ts("Please select at least one of the ticket options.");
      }
    }
  }
  if ($formName == "CRM_Event_Form_ManageEvent_EventInfo" && !empty($fields['template_title']) && ($form->_action & CRM_Core_Action::ADD)) {
    if (!empty($fields['template_title'])) {
      // Check if title already exists, else throw error.
      $title = CRM_Core_DAO::singleValueQuery("SELECT template_title FROM civicrm_event WHERE template_title = %1 AND is_template = 1", ['1' => [$fields['template_title'], 'String']]);
      if (!empty($title)) {
        $errors['template_title'] = ts('An event template already exists with this title.');
      }
    }
  }
}

function getEventTemplates($id) {
  $template = CRM_Core_DAO::singleValueQuery("SELECT template_title FROM civicrm_event WHERE is_template = 1 AND id = %1", ['1' => [$id, 'Integer']]);
  return $template;
}

/**
 * Implementation of hook_civicrm_postProcess
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postProcess
 */
function aoeventtemplates_civicrm_postProcess($formName, &$form) {
  if ($formName == "CRM_Event_Form_ManageEvent_EventInfo" && !empty($form->getVar('_templateId'))) {
    if ($form->getVar('_templateId') == FUNDRAISING_TEMPLATE) {
      return;
    }
    $eventId = CRM_Core_Session::singleton()->get('eventId');
    civicrm_api3('CustomValue', 'create', [
      'entity_id' => $eventId,
      'custom_' . TEMPLATE_ID => $form->getVar('_templateId'),
    ]);
    if ($form->getVar('_templateId') == SLOVAREVENT) {
      $dao = new CRM_Event_DAO_Event();
      $dao->event_type_id = $form->_submitValues['event_type_id'];
      $dao->title = $form->_submitValues['title'];
      $dao->orderBy('id DESC');
      $dao->find(TRUE);
      _aoeventtemplates_copyprice('Event', $dao);
    }
  }
}

function _aoeventtemplates_copyprice($objectName, &$object) {
  if ($objectName == 'Event') {
    $priceSetId = CRM_Price_BAO_PriceSet::getFor('civicrm_event', $object->id);
    if ($priceSetId) {
      $isQuickConfig = CRM_Core_DAO::getFieldValue('CRM_Price_DAO_PriceSet', $priceSetId, 'is_quick_config');
      if(!$isQuickConfig) {
        $copyPriceSet = CRM_Price_BAO_PriceSet::copy($priceSetId);
        CRM_Price_BAO_PriceSet::addTo('civicrm_event', $object->id, $copyPriceSet->id);
        CRM_Core_DAO::singleValueQuery("UPDATE civicrm_price_set SET is_reserved = 0 WHERE id = %1", [1 => [$copyPriceSet->id, "Integer"]]);
      }
    }
  }
}

function aoeventtemplates_civicrm_post($op, $objectName, $objectId, &$objectRef) {
  if ($op == 'create' && $objectName == 'Event') {
    CRM_Core_Session::singleton()->set('eventId', $objectId);
    // We also set the template ID here, some intermittent error causes session not to transmit event sometimes.
    if (!empty($objectRef->template_title)) {
      $templateID = CRM_Core_DAO::singleValueQuery("SELECT e.id FROM civicrm_event e INNER JOIN civicrm_option_value v ON v.value = e.event_type_id WHERE v.name = %1 AND v.option_group_id = 15", [1 => [$objectRef->template_title, 'String']]);
      if ($templateID == FUNDRAISING_TEMPLATE) {
        return;
      }
      civicrm_api3('CustomValue', 'create', [
        'entity_id' => $objectId,
        'custom_' . TEMPLATE_ID => $templateID,
      ]);
    }
    
  }
}
