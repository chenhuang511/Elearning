<?php
/**
 * Created by PhpStorm.
 * User: Minh Nguyen
 * Date: 5/11/2016
 * Time: 10:44 AM
 */
class Permission extends BaseModel{
    /**
     *
     * @var integer
     */
    public $id;

    /**
     * Initialize method for model.
     */

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'permissions';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return UserRole[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return UserRole
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }
    
}   