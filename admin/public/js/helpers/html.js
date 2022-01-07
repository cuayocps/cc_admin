var Html = {
  icon: function (icon) {
    return this.element('i', icon);
  },
  div: function (text, classes, attrs) {
    return this.element('div', classes, attrs ? attrs : {}).html(text);
  },
  link: function (text, classes, attrs) {
    return this.element('a', classes, attrs).html(text);
  },
  list: function (id, list, classes, renderCallback) {
    var $list = this.element('ul', 'list-group', { 'id': id });
    if (classes) {
      $list.addClass(this._classes(classes));
    }
    this.updateListItems($list, list, renderCallback);
    return $list;
  },
  select: function (name, list, value, attrs) {
    var $select = this.element('select', 'form-control', { 'name': name });
    let empty = false;
    if (attrs) {
      if (attrs.empty) {
        empty = attrs.empty;
        delete (attrs.empty);
      }
      $select.attr(attrs);
    }
    this.updateOptions($select, list, value, empty);
    return $select;
  },
  updateOptions: function (el, options, selected, empty) {
    const self = this;
    const $el = $(el);
    $el.get(0).options.length = 0;
    empty = _.isUndefined(empty) || _.isNull(empty) ? false : empty;
    if (empty !== false) {
      self.addOption($el, '', empty === true ? ' ' : empty)
    }
    _.each(options, function (text, value) {
      self.addOption($el, value, text, selected == value)
    });
  },
  updateListItems: function (el, options, renderCallback) {
    const self = this;
    const $el = $(el);
    $el.empty();
    _.each(options, function (text) {
      const _text = renderCallback ? renderCallback(text) : text;
      self.listItem($el, _text);
    });
  },
  addOption: function (el, value, text, isSelected) {
    const $option = $('<option/>').attr('value', value).text(text);
    if (isSelected === true) {
      $option.attr('selected', 'selected');
    }
    $(el).append($option);
  },
  listItem: function (el, text) {
    const $listItem = this.element('li', 'list-group-item').html(text)
    $(el).append($listItem);
  },
  calendar: function (name, date, attrs) {
    attrs = $.extend(attrs ? attrs : {}, {
      name: name,
      value: date,
      type: 'text',
      autocomplete: 'off'
    });
    return '<div class="input-group date">'
      + this.element('input', false, attrs)
      + '<div class="input-group-append">'
      + '<span class="input-group-text" id="basic-addon2"><i class="far fa-calendar-check"></i></span>'
      + '</div></div>';
  },
  submit: function (text, classes, attrs) {
    let _attrs = $.extend(attrs ? attrs : {}, {
      type: 'submit'
    });
    return this.element('button', classes, _attrs).html(text);
  },
  button: function (text, classes, attrs) {
    let _attrs = $.extend(attrs ? attrs : {}, {
      type: 'button'
    });
    return this.element('button', classes, _attrs).html(text);
  },
  switch: function (name, id, text) {
    const $input = Html.element('input', 'custom-control-input', {
      id: id,
      name: name,
      type: 'checkbox',
      value: 1
    })
    const $label = Html.element('label', 'custom-control-label mt-1 ml-2', {
      for: id
    }).html(text)
    return this.div($input + $label, 'custom-control custom-switch')
  },
  element: function (name, classes, attrs) {
    var $el = $('<' + name + '/>');
    if (attrs) {
      $el.attr(attrs);
    }
    if (classes) {
      $el.addClass(this._classes(classes));
    }
    $el.toString = this._toString.bind($el);
    return $el;
  },

  selectToText: function (el, classes) {
    if ($(el).is('.select-to-text')) {
      return;
    }
    const selectText = $(el).addClass('select-to-text').find('option:selected').get(0).text;
    $(el).hide().before(
      this.element('input', classes, {
        value: selectText
      })
    );
  },

  undoSelectToText: function (el) {
    if (!$(el).is('.select-to-text')) return;
    $(el).removeClass('select-to-text').show().prev().remove();
  },

  readOnly: function (el) {
    $(el)
      .attr('readonly', true)
      .removeClass('form-control')
      .addClass('form-control-plaintext');
  },

  undoReadOnly: function (el) {
    $(el).removeAttr('readonly').removeClass('form-control-plaintext').addClass('form-control');
  },

  _classes: function (classes) {
    if (typeof classes == 'undefined') {
      return '';
    } else if (typeof classes == 'string') {
      classes = [classes];
    }
    return classes.join(' ');
  },
  _toString: function () {
    return this.get(0).outerHTML;
  }
}
