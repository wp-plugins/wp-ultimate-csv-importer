<?php
class Modulehandler {

	/*
         *frames array with post values
        */
        public function getReturnArray(){

        foreach($_POST as $postkey=>$postvalue){
        if($postvalue != '-- Select --')
                $ret_array[$postkey]=$postvalue;
        }

        return $ret_array;
        }

	/*
	 *Forms Data array to insert 
	*/
        public function formDataArray($csvArray){
                $ret_array = $this->getReturnArray();
                $inc = 0;
                foreach($csvArray as $key => $value){
                        for($i=0;$i<count($value) ; $i++){
                                if(array_key_exists('mapping'.$i,$ret_array))
                        $new_post[$ret_array['mapping'.$i]] = $value[$i];
                        }
                foreach($new_post as $ckey => $cval)
                        $data_array[$inc][$ckey] = $new_post[$ckey];
                $inc++;
                }
                return $data_array;
        }
}
