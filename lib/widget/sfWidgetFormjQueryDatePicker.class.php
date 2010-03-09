<?php
/**
 * sfWidgetFormjQueryDatePicker represents a date widget rendered by JQuery UI.
 *
 * This widget needs JQuery and JQuery UI to work.
 *
 * @package    symfony
 * @subpackage widget
 * @author     SidGBF <sid.gbf@gmail.com>
 * @copyright  Based on sfWidgetFormjQueryDate
 */
class sfWidgetFormjQueryDatePicker extends sfWidgetForm
{
  /**
   * Configures the current widget.
   *
   * Available options:
   *
   *  * image:       The image path to represent the widget (false by default)
   *  * config:      A JavaScript array that configures the JQuery date widget
   *  * culture:     The user culture
   *  * date_widget: The date widget instance to use as a "base" class
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('image', false);
    $this->addOption('config', '{}');
    $this->addOption('culture', '');
    $this->addOption('date_widget', new sfWidgetFormDate());

    parent::configure($options, $attributes);

    if ('en' == $this->getOption('culture'))
    {
      $this->setOption('culture', 'en');
    }
  }

  /**
   * @param  string $name        The element name
   * @param  string $value       The date displayed in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $_output = '';
    $prefix = $this->generateId($name);
    if($value == null) {
      $date = array(date('Y'), date('m'), date('d'));
    } else {
      $date = substr($value, 0, 10);
      $date = explode('-', $date);
    }

    $image = '';
    if (false !== $this->getOption('image'))
    {
      $image = sprintf(', buttonImage: "%s", buttonImageOnly: true', $this->getOption('image'));
    }
    $_output .= '<div id="'.$prefix.'_widget_div">'.$this->getOption('date_widget')->render($name, $value, $attributes, $errors).'</div>';
    $_output .= '<div id="'.$prefix.'_ui_div" style="display: none;">';
    $_output .= tag('input', array('type' => 'hidden', 'disabled' => 'disabled', 'id' => $prefix.'_temp'));
    $_output .= content_tag('div', '', array('id' => $prefix.'_div_datepicker'));
    $_output .= javascript_tag($this->jsOnSelect($prefix));
    $_output .= javascript_tag('jQuery(document).ready(
  function () {
    jQuery(\'#'.$prefix.'_div_datepicker\').datepicker(
      { onSelect:   wfd_'.$prefix.'_update_linked,
        dateFormat: "yy-mm-dd",
        altField: "#'.$prefix.'_temp",
        altFormat: "DD, d MM, yy"
      }
    );
    var tempDate = jQuery.datepicker.formatDate(\'yy-mm-dd\', new Date('.$date[0].', '.$date[1].' - 1, '.$date[2].'));
    jQuery(\'#'.$prefix.'_div_datepicker\').datepicker(\'setDate\', tempDate);
    wfd_'.$prefix.'_update_linked(tempDate);
    jQuery(\'#'.$prefix.'_temp\').change();
    jQuery(\'#'.$prefix.'_ui_div\').show();
    jQuery(\'#'.$prefix.'_widget_div\').hide();
  }
);');
    $_output .= '</div>';
    return $_output;
  }
/*
    return $this->getOption('date_widget')->render($name, $value, $attributes, $errors).
           $this->renderTag('input', array('type' => 'hidden', 'size' => 10, 'id' => $id = $this->generateId($name).'_jquery_control', 'disabled' => 'disabled')).
           sprintf(<<<EOF
<script type="text/javascript">
  function wfd_%s_read_linked()
  {
    jQuery("#%s").val(jQuery("#%s").val() + "-" + jQuery("#%s").val() + "-" + jQuery("#%s").val());

    return {};
  }

  function wfd_%s_update_linked(date)
  {
    jQuery("#%s").val(parseInt(date.substring(0, 4)));
    jQuery("#%s").val(parseInt(date.substring(5, 7)));
    jQuery("#%s").val(parseInt(date.substring(8)));
  }

  function wfd_%s_check_linked_days()
  {
    var daysInMonth = 32 - new Date(jQuery("#%s").val(), jQuery("#%s").val() - 1, 32).getDate();
    jQuery("#%s option").attr("disabled", "");
    jQuery("#%s option:gt(" + (%s) +")").attr("disabled", "disabled");

    if (jQuery("#%s").val() > daysInMonth)
    {
      jQuery("#%s").val(daysInMonth);
    }
  }

  jQuery(document).ready(function() {
    jQuery("#%s").datepicker(jQuery.extend({}, {
      minDate:    new Date(%s, 1 - 1, 1),
      maxDate:    new Date(%s, 12 - 1, 31),
      beforeShow: wfd_%s_read_linked,
      onSelect:   wfd_%s_update_linked,
      showOn:     "button"
      %s
    }, jQuery.datepicker.regional["%s"], %s, {dateFormat: "yy-mm-dd"}));
  });

  jQuery("#%s, #%s, #%s").change(wfd_%s_check_linked_days);
</script>
EOF
      ,
      $prefix, $id,
      $this->generateId($name.'[year]'), $this->generateId($name.'[month]'), $this->generateId($name.'[day]'),
      $prefix,
      $this->generateId($name.'[year]'), $this->generateId($name.'[month]'), $this->generateId($name.'[day]'),
      $prefix,
      $this->generateId($name.'[year]'), $this->generateId($name.'[month]'),
      $this->generateId($name.'[day]'), $this->generateId($name.'[day]'),
      ($this->getOption('can_be_empty') ? 'daysInMonth' : 'daysInMonth - 1'),
      $this->generateId($name.'[day]'), $this->generateId($name.'[day]'),
      $id,
      min($this->getOption('date_widget')->getOption('years')), max($this->getOption('date_widget')->getOption('years')),
      $prefix, $prefix, $image, $this->getOption('culture'), $this->getOption('config'),
      $this->generateId($name.'[day]'), $this->generateId($name.'[month]'), $this->generateId($name.'[year]'),
      $prefix
    );*/
    public function jsOnSelect($name) {

return sprintf('  function wfd_%s_update_linked(date)
  {
    date = date+"";
    var splittedDate = date.split("-");
    jQuery("#%s").val(parseInt(date.substring(0, 4)));

    if(splittedDate[1][0] == 0) {
      splittedDate[1] = splittedDate[1][1];
    }
    jQuery("#%s").val(splittedDate[1]);

    if(splittedDate[2][0] == 0) {
      splittedDate[2] = splittedDate[2][1];
    }
    jQuery("#%s").val(splittedDate[2]);

    jQuery("#%s").blur();
    jQuery("#%s").change();
  }', $name, $name.'_year', $name.'_month', $name.'_day', $name, $name.'_temp');
      
    }
}
