<?php
use yii\helpers\Url;
?>

<div class="row site-index">
    <div class="w-100"><hr></div>
         <?php foreach ($cities as $key => $city) { ?>
             <div class="col-lg-2 col-md-3 col-sm-4 col-6 font-14">
                 <a href="<?= Url::toRoute(["/".$city->city->domain."/"])?>"><?= $city->_text->name?></a> <span class="ads-amount-city"> <?= $city->city->ads_amount ?: 0 ?></span>
             </div>
         <?php } ?>
    <div class="w-100"><hr></div>
    <? $div_row_unneeded = true; ?>
    <?= $this->render('/categories/list', compact('categories', 'div_row_unneeded'));?>

</div>
<?=  $this->render('/partials/_ads_list.php',
    [
        'padding_top_20' => true,
        'ads_search' => $ads_search,
        'library_search'=> $library_search,
        'title' => countString($ads_search['count'], [__('proposal'), __('proposals_im_p'), __('proposals_r_p') ]),
        'no_ads_title' => __('No ads found'),
        'show_sn_widgets' => true,
//            'current_category' => $current_category,
//            'current_action' => $current_action
    ]) ?>
