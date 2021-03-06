<?php
use yii\helpers\Url;
?>
<div class="row">
    <div class="col-lg-12 col-xs-12">
        <input
            class="form-control bs-autocomplete"
            id="live-search-select"
            value=""
            placeholder="<?= __('Select a city') ?>"
            type="text"
            data-hidden_field_id="hidden-city"
            data-item_id="live-search-select"
            data-item_label="text"
            autocomplete="off">
        <hr>
    </div>
    <div class="col-lg-12 col-xs-12 reset-location">
        <a href="<?= Url::toRoute(["/"])?>"><?= __('Reset location') ?></a>
    </div>
    <?php foreach ($regions as $region) { ?>
        <div class="col-lg-4 col-md-6 col-xs-2">
            <ul class="lvl-block">
                <li class="lvl-1">
                    <a href="<?= Url::toRoute(["/".$region["domain"]."/"])?>"><?= $region['name']?></a>
                </li>
                <?php foreach (\common\models\Region::getCitiesByRegionId($region['id']) as $city) { ?>
                    <li class="lvl-2 ml-4 city-list"><a href="<?= Url::toRoute(["/".$city['domain']."/"])?>"><?= $city['name']?></a></li>
                <?php } ?>
            </ul>
        </div>

    <?php } ?>
</div>
<script type="text/javascript">
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
        $('.bs-autocomplete').each(function() {
            var _this = $(this),
                _data = _this.data(),
                _search_data = [],
                _visible_field = $('#' + _data.item_id),
                _hidden_field = $('#' + _data.hidden_field_id),
                _selected_text = '',
                _selected_id = '';


            _this.after('<div class="bs-autocomplete-feedback form-control-feedback"><div class="loader"><?= __('Search...') ?></div></div>')
                .parent('.form-group').addClass('has-feedback');

            var feedback_icon = _this.next('.bs-autocomplete-feedback');
            feedback_icon.hide();

            _this.autocomplete({
                minLength: 3,
                autoFocus: true,

                source: function(request, response) {
                    _hidden_field.val('');
                    $.ajax({
                        dataType: "json",
                        type : 'POST',
                        url: '<?= \yii\helpers\Url::toRoute('cities/search-cities-for-select') ?>',
                        data: {q: $('input#live-search-select').val()},
                        success: function(data) {
                            _search_data = data
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

                select: function(event, ui) {console.log(ui);
                    _hidden_field.val(ui.item.id);
                    _visible_field.val(ui.item.text);
                    _selected_text = ui.item.text;
                    _selected_id = ui.item.id;
                    window.location.href = window.location.origin + "/select-location/" + ui.item.domain + "/"
                },
                close: function( event, ui ) {

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