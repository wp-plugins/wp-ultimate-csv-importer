<?php
/******************************
 * filename:    modules/categories/actions/actions.php
 * description:
 */

class CategoriesActions extends SkinnyActions {

    public function __construct()
    {
    }

  /**
   * The actions index method
   * @param array $request
   * @return array
   */
    public function executeIndex($request)
    {
        // return an array of name value pairs to send data to the template
        $data = array();
        return $data;
    }

}