class contentBlock {
  label;
  name;
  widget;
  required;
  default;
  hint;
  options;
  type;

  constructor(
    label,
    name,
    widget,
    required = false,
    hint = "",
    options = [],
    type
  ) {
    this.label = label;
    this.name = name;
    this.widget = widget;
    this.required = required;
    this.hint = hint;
    this.options = options;
    this.type = type;
  }

  getLabel() {
    return this.label;
  }

  getName() {
    return this.name;
  }

  getWidget() {
    return this.widget;
  }

  isRequired() {
    return this.required;
  }

  getHint() {
    return this.hint;
  }

  getOptions() {
    return this.options;
  }

  getType() {
    return this.type;
  }
}
