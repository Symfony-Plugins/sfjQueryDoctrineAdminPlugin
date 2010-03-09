<?php
  function getDefaultHint() {
    return __(sfConfig::get('app_default_hint', '<em>Clique aqui para definir um valor</em>'));
  }

  function content_to_view(sfFormField $field, $attributes, $help) {
    $value = '';
    if($field->getValue()) {
      $value = $field->getValue();
    } else if($help) {
      $value = $help;
    } else if($field->renderHelp()) {
      $value = $field->renderHelp();
    } else {
      $value = getDefaultHint();
    }

    return $value;
  }
  function form_event_handlers($form) {
    include_jquery();
    $command = 'jQuery(document).ready(function () {'."\r\n";
    foreach($form as $fieldName => $field) {
      if($field->isHidden())
        continue;
      $command .= '  if(document.getElementById("'.$field->renderId().'_temp")) {' . "\r\n";
      $command .= '    jQuery("#'.$field->renderId().'_temp").change( function() { reset_function(); update_values("'.$field->renderId().'") } );' . "\r\n";
      $command .= '  } else {' . "\r\n";
      $command .= '    jQuery("#'.$field->renderId().'").blur( function() { reset_function(); update_values("'.$field->renderId().'") } );' . "\r\n";
      $command .= '  }' . "\r\n";
      $command .= '  jQuery("#'.$field->renderId().'_view").click( function() { switch_field("'.$field->renderId().'") } );' . "\r\n";
    }
    $command .= '  reset_function();'."\r\n";
    $command .= '});'."\r\n";
    $command .= _form_hide_fields_function();
    $command .= _form_show_labels_function();
    $command .= _form_reset_function();
    $command .= _form_switch_function();
    $command .= _form_update_values_function();
    echo javascript_tag($command);
  }
  function _form_hide_fields_function() {
    return 'function hide_all_fields() {
  jQuery(".content_edit").hide();
}'."\r\n";
  }
  function _form_show_labels_function() {
    return 'function show_labels() {
  jQuery(".content_view").show();
}'."\r\n";
  }
  function _form_reset_function() {
    return 'function reset_function() {
  hide_all_fields();
  show_labels();
}'."\r\n";
  }
  function _form_switch_function() {
    return 'function switch_field(fieldName) {
  reset_function();
  jQuery("#"+fieldName+"_edit").show();
  jQuery("#"+fieldName+"_view").hide();
  if(jQuery("#"+fieldName)) jQuery("#"+fieldName).focus();
}'."\r\n";
  }
  function _form_update_values_function() {
    return 'function update_values(fieldName) {
  var newValue = "";

  if(document.getElementById(fieldName+"_temp")) {
    newValue = jQuery("#"+fieldName+"_temp").val();
  } else {
    newValue = jQuery("#"+fieldName).val();
  }
  if(newValue == "") {
    newValue = jQuery("#"+fieldName+"_edit > .help").html();
  }
  jQuery("#"+fieldName+"_view > .help").html(newValue);

}'."\r\n";
  }
  function javascript_tag($content)
  {
    return content_tag('script', javascript_cdata_section($content), array('type' => 'text/javascript'));
  }

  function javascript_cdata_section($content)
  {
    return "\n//".cdata_section("\n$content\n//")."\n";
  }
  function include_jquery() {
    $jquery = sfConfig::get('app_jquery_path', '/jquery.js');
    use_javascript($jquery);
  }
?>
