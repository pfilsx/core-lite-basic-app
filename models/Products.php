<?php 

namespace app\models;

use Core;

/**
* This is the model class for table "sl_products"
* @property integer id_product
* @property string name
* @property integer fid_category
* @property string image
* @property string description
* @property integer price
 */
class Products extends \core\components\ActiveModel
{

    /**
    * @inheritdoc
    */
    public static function schemaTableName(){
        return 'sl_products';
    }

    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
            [['name', 'fid_category'], 'required'],
            [['fid_category', 'price'], 'integer'],
            [['description'], 'string'],
            [['name', 'image'], 'string', 'max' => 255],
        ];
    }
    /**
    * @inheritdoc
    */
    public function beforeSave()
    {
    }
    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
            'id_product' => 'auto incremented',
            'name' => 'name for product',
            'fid_category' => 'foreign to sl_categories',
            'image' => 'image for product',
            'description' => 'description for product',
            'price' => 'price for product',
        ];
    }
}