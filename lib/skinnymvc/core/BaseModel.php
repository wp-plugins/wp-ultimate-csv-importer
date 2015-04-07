<?php
/**
 * filename:    BaseModel.php
 * description: Base class for all Base model classes
 */

abstract class SkinnyBaseModel {

    // P R O C E D U R E S //////////////////////////////////////////////////////////////////////////////////////////////////
        /**
         * Gets SQL query results
         * @param string $tableName Name of the affected table
         * @param mixed $criteria
         * @return array
         */
        public static function selectArray($tableName, $criteria = array(), $dbKey)
        {

            if (  isset($criteria["sql"]) && is_string($criteria["sql"])  ) {

                $sql = $criteria["sql"];

            } else {

                //columns can be SQL or an array.
                if (empty($criteria['columns'])) {
                    $criteria['columns'] = '*';
                } else {
                    if (is_array($criteria['columns'])) {
                        $criteria['columns'] = implode (',', $criteria['columns']);
                    }
                }

                //joins are only SQL for now
                if (empty($criteria['joins'])) {
                    $criteria['joins'] = '';
                }

                // group can be SQL or array
                if (empty($criteria['group'])) {
                    $criteria['group'] = '';
                } else {
                    if(is_array($criteria['group'])) {
                        $criteria['group'] = 'GROUP BY '.implode(',',$criteria['group']);
                    }
                }

                //limit can be SQL or a STRING formatted like this: "LIMIT 10" or "LIMIT 5,10" or "10"
                if (empty($criteria['limit'])) {
                    $criteria['limit'] = '';
                }else{
                    if (is_numeric($criteria['limit'])) {
                        $criteria['limit'] = "LIMIT ".$criteria['limit'];
                    }
                }

                //offset can be SQL or a STRING formatted like this: "OFFSET 10" or "10"
                if (empty($criteria['offset'])) {
                    $criteria['offset'] = '';
                }else{
                    if (is_numeric($criteria['offset'])) {
                        $criteria['offset'] = "OFFSET ".$criteria['offset'];
                    }
                }

                //order can be SQL or array
                //   array(
                //         array('column'=>'column1', 'direction'=>'desc'),
                //         array('column'=>'column2', 'direction'=>'desc')
                //   );
                if (empty($criteria['order'])) {
                    $criteria['order'] = '';
                } else {
                    if(is_array($criteria['order'])) {
                        $tmpOrder = "ORDER BY ";
                        foreach($criteria['order'] As $order) {
                            if (is_array($order)) {
                                $tmpOrder .= $order['column'].' '.$order['direction'];
                            } else {
                                $tmpOrder .= $order;
                            }
                            $tmpOrder .= ', ';
                        }
                        $tmpOrder = substr($tmpOrder, 0, strlen($tmpOrder)-2);
                        $criteria['order'] = $tmpOrder;
                    }
                }

                //conditions could be SQL code or an array
                //if an array, it should look like this:
                //   array( 
                //          array('left'=>'column1', 'condition'=>'<','right'=>'10'),
                //          array('left'=>'column1', 'condition'=>'NOT NULL'),
                //   );
                if (empty($criteria['conditions'])) {
                    $criteria['conditions'] = '';
                } else {
                    if (is_array($criteria['conditions'])) {
                        $tmpConditions = 'WHERE';
                        foreach($criteria['conditions'] As $condition) {
                            if(is_array($condition)) {
                                if (empty($condition['left'])) {
                                    throw new SkinnyException('Missing left side of the condition.');
                                }
                                if (empty($condition['condition'])) {
                                    throw new SkinnyException('Invalid condition.');
                                }
                                if (!isset($condition['right'])) {
                                    if (!in_array(strtoupper($condition['condition']), array('NOT NULL', 'IS NULL'))) {
                                        throw new SkinnyException('Missing right side of the condition.');
                                    } else {
                                        $tmpConditions .= $condition['left'].' '.$condition['condition'];
                                    }
                                } else {
                                    $tmpConditions .= $condition['left'].' '.$condition['condition'].' '.$condition['right'];
                                }
                                $tmpConditions .= "\n AND ";
                            } else {
                                $tmpConditions .= $condition."\n AND ";
                            }
                        }
                        $tmpConditions .= " 1=1\n";
                        $criteria['conditions'] = $tmpConditions;
                    }
                }

                $sql = "SELECT ".$criteria['columns']."\n"
                     ."FROM ".$tableName."\n";
                if (!empty($criteria['joins'])) {
                    $sql .= $criteria['joins']."\n";
                }
                if (!empty($criteria['conditions'])) {
                    $sql .= $criteria['conditions']."\n";
                }
                if (!empty($criteria['group'])) {
                    $sql .= $criteria['group']."\n";
                }
                if (!empty($criteria['order'])) {
                    $sql .= $criteria['order']."\n";
                }
                if (!empty($criteria['limit'])) {
                    $sql .= $criteria['limit']."\n";
                }
                if (!empty($criteria['offset'])) {
                    $sql .= $criteria['offset']."\n";
                }

            }


            if (SkinnySettings::$CONFIG['debug']) {
                global $__DEBUG;
                array_push($__DEBUG['sql'], $sql);
            }

            $con = SkinnyDbController::getReadConnection($dbKey);
            $result = $con->query($sql);
            if (!empty($result)) {
                return $result->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return null;
            }
        }
    ////////////////////////////////////////////////////////////////////////////////////////////////// P R O C E D U R E S //



    // M E T H O D S ////////////////////////////////////////////////////////////////////////////////////////////////////////
        public function isValid()
        {
            return TRUE;
        }

    //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=//
    // p r o t e c t e d   m e t h o d s                                                                                   //
    //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=//

        protected function assertMaySave()
        {
            // Nothing here.
        }

    //---------------------------------------------------------------------------------------------------------------------//

        protected function preSave()
        {
            // Nothing here.
        }

    //---------------------------------------------------------------------------------------------------------------------//

        protected function postSave()
        {
            // Nothing here.
        }
    //////////////////////////////////////////////////////////////////////////////////////////////////////// M E T H O D S //
}

