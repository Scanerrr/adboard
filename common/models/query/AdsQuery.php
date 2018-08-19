<?php

namespace common\models\query;

use common\models\Ads;

/**
 * This is the ActiveQuery class for [[\common\models\Ads]].
 *
 * @see \common\models\Ads
 */
class AdsQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        return $this->andWhere(['status' => Ads::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Ads[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Ads|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
