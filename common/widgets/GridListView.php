<?php
/**
 * Created by PhpStorm.
 * User: fron-
 * Date: 5/13/2018
 * Time: 22:54
 */

namespace common\widgets;


use yii\widgets\ListView;

class GridListView extends ListView
{
    /**
     * Renders all data models.
     * @return string the rendering result
     */
    public function renderItems()
    {
        $models = $this->dataProvider->getModels();
        $keys = $this->dataProvider->getKeys();
        $rows = [];
        $bsCol = 0;
        foreach (array_values($models) as $index => $model) {
            $key = $keys[$index];
            if ($bsCol === 0) {
                $rows[] = '<div class="row">';
            }
            $bsCol++;
            $rows[] = '<div class="col-sm-3">';
            if (($before = $this->renderBeforeItem($model, $key, $index)) !== null) {
                $rows[] = $before;
            }

            $rows[] = $this->renderItem($model, $key, $index);

            if (($after = $this->renderAfterItem($model, $key, $index)) !== null) {
                $rows[] = $after;
            }
            $rows[] = "</div>";
            if ($bsCol === 4) {
                $rows[] = '</div>';
                $bsCol = 0;
            }
        }
        if ($bsCol !== 0) {
            $rows[] = '</div>';
        }

        return implode($this->separator, $rows);
    }

}