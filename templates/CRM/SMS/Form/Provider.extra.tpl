	{*
 +--------------------------------------------------------------------+
 | CiviCRM version 4.6                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2015                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*}
{* This template is used for adding/configuring SMS Providers  *}
{if $form.is_auto_reply}
  <table>
    <tr class="crm-job-form-block-is_auto_reply">
        <td class="label">{$form.is_auto_reply.label}</td><td>{$form.is_auto_reply.html}</td>
    </tr>
    <tr class="crm-job-form-block-auto_reply_message">
        <td class="label">{$form.auto_reply_message.label}<span title="This field is required." class="crm-marker">*</span></td><td>{$form.auto_reply_message.html}</td>
    </tr>
  </table>

<script type="text/javascript" >
{literal}
  CRM.$(function($) {	
    $($('tr.crm-job-form-block-is_auto_reply')).insertAfter('tr.crm-job-form-block-api_params');
    $($('tr.crm-job-form-block-auto_reply_message')).insertAfter('tr.crm-job-form-block-is_auto_reply');
    $('#is_auto_reply').click( function() {
      hideShowBlock(this);
    });
    hideShowBlock($('#is_auto_reply'));
    function hideShowBlock(obje) {
      if ($(obje).prop('checked')) {
        $('tr.crm-job-form-block-auto_reply_message').show();
      }
      else {
        $('tr.crm-job-form-block-auto_reply_message').hide( );
      }     
    }
  });
{/literal}
</script>
{/if}