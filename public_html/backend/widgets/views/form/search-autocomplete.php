<?
/**
 *  $attribute - array(label, name, model, type, url, placeholder, input_id)
 *      label - название поля;
 *      current_value - [id => , title => ], текущее значение
 *      name - название инпута (name="name");
 *      model - класс модели;
 *      previous_input[id - ид инпута значение которого будет использоватся jquery для ajax-запроса; query_name - название параметра, которое будет использоваться при ajax-запросе]
 *      type - Form::SEARCH_AUTOCOMPLETE;
 *      url - урл для ajax поиска;
 *      input_id - id, который присвоиться инпуту, если не предоставлен, то притсваивается 'input-.uniqid()'
 *      placeholder;
 */
$id = (isset($attribute['input_id'])) ? $attribute['input_id'] : 'input-'.uniqid();
$prev_input_id = null;
$additional_query = null;
$prev_input_id = (isset($attribute['previous_input'])) ? $attribute['previous_input']['id'] : null;
$additional_query = (isset($attribute['previous_input'])) ? $attribute['previous_input']['query_name'] : null;
$model_name = $attribute['model_name'];
$visible_value = ($attribute['current_value']['title']) ? $attribute['current_value']['title'] : null;
$hidden_value = ($attribute['current_value']['id']) ? $attribute['current_value']['id'] : null;
$placeholder = (isset($attribute['placeholder'])) ? $attribute['placeholder'] : 'Начните печатать...'
?>
<div class="form-group row validation-errors">
    <label class="col-xs-2 col-form-label"><?= $attribute['label'] ?></label>
    <div class="col-xs-10">
    <input
        class="form-control bs-autocomplete-<?= $id ?>"
        id="<?= $id ?>"
        value="<?= $visible_value ?>"
        type="text"
        placeholder="<?= $placeholder ?>"
        data-hidden_field_id="hidden-<?= $id ?>"
        data-item_id="<?= $id ?>"
        data-item_label="text"
        autocomplete="off">
        <input type="hidden" id="hidden-<?= $id ?>" name="<?= $model_name ?>[<?= $attribute['name'] ?>]" value="<?= $hidden_value ?>">
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#<?= $id ?>').on('keyup', function(){
            var visible_val = $('#<?= $id ?>').val();
            var hidden_val = $('#hidden-<?= $id ?>').val();
            if(visible_val != hidden_val){
                $('#hidden-<?= $id ?>').val('');
            }
        })
    });
    $.widget("ui.autocomplete", $.ui.autocomplete, {

        _renderMenu: function(ul, items) {
            var that = this;
            ul.attr("class", "nav nav-pills nav-stacked  bs-autocomplete-menu list-group");
            $.each(items, function(index, item) {
                that._renderItemData(ul, item);
            });
        },

        _resizeMenu: function() {
            var ul = this.menu.element;
            ul.outerWidth(Math.min(
                ul.width("").outerWidth() + 1,
                this.element.outerWidth()
            ));
        }

    });

    (function() {
        $('.bs-autocomplete-<?= $id ?>').each(function() {
            var _this = $(this),
                _data = _this.data(),
                _search_data = [],
                _visible_field = $('#' + _data.item_id),
                _hidden_field = $('#' + _data.hidden_field_id);


            _this.after('<div class="bs-autocomplete-feedback form-control-feedback"><div class="loader"><?= __('Поиск...') ?></div></div>')
                .parent('.form-group').addClass('has-feedback');

            var feedback_icon = _this.next('.bs-autocomplete-feedback');
            feedback_icon.hide();

            _this.autocomplete({
                minLength: 3,
                autoFocus: true,

                source: function(request, response) {
                    _hidden_field.val('');
                    var params = {query: $('#'+'<?= $id ?>').val()};
                    <? if($additional_query){ ?>
                        params['<?= $additional_query ?>'] = $('#<?= $prev_input_id ?>').val();
                    <? } ?>
                    $.ajax({
                        dataType: "json",
                        type : 'POST',
                        url: '<?= \yii\helpers\Url::toRoute($attribute['url']) ?>',
                        data: params,
                        success: function(data) {
                            _search_data = data;
                            $('input.suggest-user').removeClass('ui-autocomplete-loading');
                            if(_search_data.length == 0){
                                _hidden_field.val('');
                            }
                            response(data);
                        }
                    });
                },

                search: function() {
                    feedback_icon.show();
                    _hidden_field.val('');
                },

                response: function() {
                    feedback_icon.hide();
                },

                focus: function(event, ui) {
                    event.preventDefault();
                },

                select: function(event, ui) {
                    _visible_field.val(ui.item.text);
                    _hidden_field.val(ui.item.id);
                },
                close: function( event, ui ) {
                    if (_search_data.length != 0 && typeof _search_data[_hidden_field.val()] != "undefined") {
                        _visible_field.val(_search_data[_hidden_field.val()].text);
                    }
                }
            })
                .data('ui-autocomplete')._renderItem = function(ul, item) {
                return $('<li class="list-group-item" ></li>')
                    .data("item.autocomplete", item)
                    .append('<a>' + item[_data.item_label] + '</a>')
                    .appendTo(ul);
            };
        });
    })();
</script>