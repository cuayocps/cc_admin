function GroupsSelector(target, groups, selecteds) {
  this.$target = $(target);
  this.selecteds = selecteds;
  this.groups = groups ? groups : {};
  this.$select = null;
  this.$list = null;
  this.$addButton = null;

  this.updateTarget = function () {
    var self = this;
    var groups = {};
    $.each(this.selecteds, function (x, id) {
      groups[id] = self.groups[id];
    });
    var value = JSON.stringify(groups);
    this.$target.val(value)
  }

  this.updateSelect = function (selected) {
    var self = this;
    if (!selected) {
      selected = '';
    }
    this.$select.html('');
    var $option = Html.e('option', null, {'value': ''}).html('');
    self.$select.append($option);
    $.each(this.groups, function (value, text) {
      var attrs = {'value': value};
      if (value == selected) {
        attrs.selected = 'selected';
      }
      var $option = Html.e('option', null, attrs).html(text);
      self.$select.append($option);
    });
    this.$select.selectpicker('refresh');
  }

  this.updateList = function () {
    var self = this;
    this.$list.html('');
    var $icon = Html.e('i', 'fa fa-trash');
    var $delete = Html.e('button', 'btn btn-danger btn-xs delete badge', {type: 'button'}).html($icon);
    $.each(this.selecteds, function (x, id) {
      var $li = Html.e('li', 'list-group-item').data('id', id).html($delete + self.groups[id]);
      self.$list.append($li);
    });
    this.updateTarget();
  }

  this.id = function (type) {
    return this.$target.attr('id') + '__' + type;
  }

  this.createSelect = function (id) {
    var id = this.id('select');
    $select = Html.e('select', 'form-control', {
      "id": id,
      "data-live-search": "true"
    });
    $select.insertAfter(this.$target);
    this.$select = $select;
  }

  this.createAddButton = function () {
    var $ig = Html.e('div', 'input-group');
    var $igb = Html.e('div', 'input-group-btn');
    var $i = Html.e('i', 'fa fa-plus');
    this.$addButton = Html.e('button', 'btn btn-default add', {type: 'button'}).html($i)
    $igb.append(this.$addButton);
    $ig.insertBefore(this.$select)
    $ig.append(this.$select);
    $ig.append($igb);
  }

  this.createList = function (id) {
    var id = this.id('list');
    $list = Html.e('ul', 'list-group mt-1', { id: id });
    $list.insertAfter(this.$select);
    this.$list = $list;
  }

  this.destroy = function () {
    var $select = $('#' + this.id('select'));
    if ($select.length) {
      $select.selectpicker('destroy');
      $select.closest('.input-group').remove();
    }
    var $list = $('#' + this.id('list'));
    if ($list.length) {
      $list.remove();
    }
  }

  this.listenSelect = function () {
    var self = this;
    this.$select.on('loaded.bs.select', function () {
      var $el = self.$select.closest('div');
      $el.addClass('selectpicker-aggregable');
      $el.find('.dropdown-menu.inner').on('click', '.no-results', function () {
        var $text = $el.find('.dropdown-menu.open .bs-searchbox input[type=text]');
        var value = Math.random().toString(36).substr(2, 5);
        self.groups[value] = $text.val();
        self.updateSelect(value);
      });
    });
  }

  this.listenList = function () {
    var self = this;
    this.$list.on('click', '.delete', function (e) {
      e.preventDefault();
      var id = $(this).closest('li').data('id');
      var i = self.selecteds.indexOf(id);
      if (i !== -1) {
        self.selecteds.splice(i, 1);
        self.updateList()
      }
    })
  }

  this.listenAddButton = function () {
    var self = this;
    this.$addButton.on('click', function () {
      var id = self.$select.val();
      if (!id || self.selecteds.indexOf(id) !== -1) {
        return;
      }
      self.selecteds.push(id);
      self.updateList();
      self.updateSelect();
    });
  }

  this.init = function () {
    this.destroy();
    this.createSelect();
    this.createList();
    this.createAddButton();
    $select.selectpicker({
      'liveSearch': true,
      'noneResultsText':'Agregar: <b>{0}</b>'
    });
    this.listenSelect()
    this.listenAddButton();
    this.listenList();
  }

  this.init();
  this.updateSelect();
  this.updateList();
}

