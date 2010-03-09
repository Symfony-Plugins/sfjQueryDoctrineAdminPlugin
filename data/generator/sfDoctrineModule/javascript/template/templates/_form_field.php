[?php if ($field->isPartial()): ?]
  [?php include_partial('<?php echo $this->getModuleName() ?>/'.$name, array('form' => $form, 'attributes' => $attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes)) ?]
[?php elseif ($field->isComponent()): ?]
  [?php include_component('<?php echo $this->getModuleName() ?>', $name, array('form' => $form, 'attributes' => $attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes)) ?]
[?php else: ?]
  <div class="[?php echo $class ?][?php $form[$name]->hasError() and print ' errors' ?]">
    [?php echo $form[$name]->renderError() ?]
    <div>
      [?php echo $form[$name]->renderLabel($label) ?]
      <div class="content_view" id="[?php echo $form[$name]->renderId(); ?]_view" style="display: none;">
        <div class="help">[?php echo content_to_view($form[$name], ($attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes), $help) ?]&nbsp;</div>
      </div>
      <div class="content_edit" id="[?php echo $form[$name]->renderId(); ?]_edit">
          <div class="content">[?php echo $form[$name]->render($attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes) ?]</div>

          [?php if ($help): ?]
            <div class="help">[?php echo __($help, array(), '<?php echo $this->getI18nCatalogue() ?>') ?]</div>
          [?php elseif ($help = $form[$name]->renderHelp()): ?]
          [?php else: ?]
            <div class="help">[?php echo getDefaultHint(); ?]</div>
          [?php endif; ?]
      </div>
    </div>
  </div>
[?php endif; ?]
