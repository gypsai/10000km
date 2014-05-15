<?php

/**
 * This is the model class for table "topic_comment".
 *
 * The followings are the available columns in table 'topic_comment':
 * @property integer $id
 * @property integer $topic_id
 * @property string $content
 * @property integer $author_id
 * @property string $create_time
 * @property integer $deleted
 *
 * The followings are the available model relations:
 * @property User $author
 * @property Topic $topic
 */
class TopicCommentAR extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return TopicCommentAR the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'topic_comment';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('topic_id, content', 'required'),
            array('topic_id,', 'numerical', 'integerOnly' => true),
            array('content', 'length', 'tooLong' => '您输入的内容需少于256个字符', 'max' => 256, 'min' => 1, 'tooShort' => '您输入的内容需大于3个字符'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, topic_id, content, author_id, create_time, deleted', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'author' => array(self::BELONGS_TO, 'User', 'author_id'),
            'topic' => array(self::BELONGS_TO, 'Topic', 'topic_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'topic_id' => 'Topic',
            'content' => 'Content',
            'author_id' => 'Author',
            'create_time' => 'Create Time',
            'deleted' => 'Deleted',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('topic_id', $this->topic_id);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('author_id', $this->author_id);
        $criteria->compare('create_time', $this->create_time, true);
        $criteria->compare('deleted', $this->deleted);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }
    
    
    protected function beforeSave() {
        if ($this->isNewRecord) {
            if (empty($this->author_id)) $this->author_id = Yii::app()->user->id;
            $this->create_time = date('Y-m-d H:i:s');
            $this->deleted = 0;
        }
        !isset($this->upid) && $this->upid = 0;
        return parent::beforeSave();
    }
    
    public function getFirstError(){
        
        if (defined('YII_DEBUG')) {
            return CJSON::encode($this->getErrors());
        }
        $errors = $this->getErrors();
        $es = array_pop($errors);
        return array_pop($es);
    }

}