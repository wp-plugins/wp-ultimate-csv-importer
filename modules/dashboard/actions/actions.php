<?php
/*********************************************************************************
 * WP Ultimate CSV Importer is a Tool for importing CSV for the Wordpress
 * plugin developed by Smackcoder. Copyright (C) 2014 Smackcoders.
 *
 * WP Ultimate CSV Importer is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Affero General Public License version 3
 * as published by the Free Software Foundation with the addition of the
 * following permission added to Section 15 as permitted in Section 7(a): FOR
 * ANY PART OF THE COVERED WORK IN WHICH THE COPYRIGHT IS OWNED BY WP Ultimate
 * CSV Importer, WP Ultimate CSV Importer DISCLAIMS THE WARRANTY OF NON
 * INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * WP Ultimate CSV Importer is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public
 * License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program; if not, see http://www.gnu.org/licenses or write
 * to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA 02110-1301 USA.
 *
 * You can contact Smackcoders at email address info@smackcoders.com.
 *
 * The interactive user interfaces in original and modified versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License
 * version 3, these Appropriate Legal Notices must retain the display of the
 * WP Ultimate CSV Importer copyright notice. If the display of the logo is
 * not reasonably feasible for technical reasons, the Appropriate Legal
 * Notices must display the words
 * "Copyright Smackcoders. 2014. All rights reserved".
 ********************************************************************************/

class DashboardActions extends SkinnyActions {

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

                    public function ts2short ($ts)
                      { 
                    return date("j M Y", $ts);						
                     }

 		public function getStatsWithDate(){
                        ob_clean();
			global $wpdb;
                        $blog_id = 1;
                        $returnArray = array();
                        $random = array();
                        $chart = array();
                        $get_imptype = array();
	                //    $i = 0;
                   $today = date("Y-m-d H:i:s"); 
                     for ($i = 0; $i <= 11; $i++) {
    $mons[]= date("M", strtotime( $today." -$i months"));
  
                               }  

                       $m2 = '';
                        foreach($mons as $ran_month)
                           { 
                             $m2[] = $ran_month ;
                          }
                      
                       $j=0;
                    $get_imptype = array('Post','Page','Comments','Custom Post','Users','Eshop');
                    foreach($get_imptype as $imp_type)
                    {
   $lid = $wpdb->get_results("select inserted from smackcsv_line_log where imported_type = '{$imp_type}' and imported_on >= DATE_SUB(NOW(),INTERVAL 1 YEAR)");
                              foreach($lid as $ll) {
                                     $change = $ll->inserted;
                                      $today = date("Y-m-d H:i:s"); 

                       }
                     for ($i = 0; $i <= 11; $i++) {
    $month[]= date("M", strtotime( $today." -$i months"));
                               }  
                        $mon = '';
                        foreach($month as $mm)
                           { 
                             $m1[] = $mm ;
                          }
                       $random['m'] =$m1;
                     for ($i = 0; $i <= 11; $i++) {
    $years[]= date("Y", strtotime( $today." -$i months"));
                               }  
                        $mon = '';
                        foreach($years as $yy)
                           { 
                             $y1[] = $yy ;
                          }
                       $random['y'] =$y1;

        $chart1='';
        $total ='';$total1='';$total2='';$total3='';$total4='';$total5='';$total6='';$total7='';$total8='';$total9='';$total10='';$total11='';
        $tot = $wpdb->get_results("select sum(inserted) as ins from smackcsv_line_log where month ='{$random['m'][0]}' and imported_type = '{$imp_type}' and year = {$random['y'][0]} ");
        foreach($tot as $tt) { $total .= (int)$tt ->ins.","; } $chart1 .= $total ;
        
        $tot1 = $wpdb->get_results("select sum(inserted) as ins from smackcsv_line_log where month ='{$random['m'][1]}'  and imported_type = '{$imp_type}' and year = {$random['y'][1]}");
        foreach($tot1 as $tt1) { $total1 .= (int)$tt1 ->ins.","; } $chart1 .= $total1  ;
        
        $tot2 = $wpdb->get_results("select sum(inserted) as ins from smackcsv_line_log where month ='{$random['m'][2]}' and imported_type = '{$imp_type}' and year = {$random['y'][2]} ");
        foreach($tot2 as $tt2) { $total2 .= (int)$tt2->ins.","; } $chart1 .= $total2 ;
        
        $tot3 = $wpdb->get_results("select sum(inserted) as ins from smackcsv_line_log where month ='{$random['m'][3]}' and imported_type = '{$imp_type}' and year = {$random['y'][3]}");
        foreach($tot3 as $tt3) { $total3 .= (int)$tt3 ->ins.","; } $chart1 .= $total3 ;
                                
        $tot4 = $wpdb->get_results("select sum(inserted) as ins from smackcsv_line_log where month ='{$random['m'][4]}' and imported_type = '{$imp_type}' and year = {$random['y'][4]} ");
        foreach($tot4 as $tt4) { $total4 .= (int)$tt4 ->ins.","; } $chart1 .= $total4 ;
        
        $tot5 = $wpdb->get_results("select sum(inserted) as ins from smackcsv_line_log where month ='{$random['m'][5]}'  and imported_type = '{$imp_type}' and year = {$random['y'][5]}");
        foreach($tot5 as $tt5) { $total5 .= (int)$tt5 ->ins.","; } $chart1 .= $total5 ;
        
        $tot6 = $wpdb->get_results("select sum(inserted) as ins from smackcsv_line_log where month ='{$random['m'][6]}' and imported_type = '{$imp_type}' and year = {$random['y'][6]} ");
        foreach($tot6 as $tt6) { $total6 .= (int)$tt6 ->ins.","; } $chart1 .= $total6 ;
        
        $tot7 = $wpdb->get_results("select sum(inserted) as ins from smackcsv_line_log where month ='{$random['m'][7]}' and imported_type = '{$imp_type}' and year = {$random['y'][7]} ");
        foreach($tot7 as $tt7) { $total7 .= (int)$tt7 ->ins.","; } $chart1 .= $total7 ;
                
        $tot8 = $wpdb->get_results("select sum(inserted) as ins from smackcsv_line_log where month ='{$random['m'][8]}'  and imported_type = '{$imp_type}' and year = {$random['y'][8]}");
        foreach($tot8 as $tt8) { $total8 .= (int)$tt8 ->ins.","; } $chart1 .= $total8 ;
        
        $tot9 = $wpdb->get_results("select sum(inserted) as ins from smackcsv_line_log where month ='{$random['m'][9]}' and imported_type = '{$imp_type}' and year = {$random['y'][9]} ");
        foreach($tot9 as $tt9) { $total9 .= (int)$tt9 ->ins.","; } $chart1 .= $total9 ;
        
        $tot10 = $wpdb->get_results("select sum(inserted) as ins from smackcsv_line_log where month ='{$random['m'][10]}' and imported_type = '{$imp_type}' and year = {$random['y'][10]} ");
        foreach($tot10 as $tt10) { $total10 .= (int)$tt10 ->ins.","; } $chart1 .= $total10 ;
        
        $tot11 = $wpdb->get_results("select sum(inserted) as ins from smackcsv_line_log where month ='{$random['m'][11]}' and imported_type = '{$imp_type}' and year ={$random['y'][11]} ");
        foreach($tot11 as $tt11) { $total11 .= (int)$tt11 ->ins; } $chart1 .= $total11 ;
                      $chart[] = $chart1;
                     $returnArray['cat'] = $m2;
                     $returnArray[$j]['name'] = $imp_type;
                     $last_year = array_map('intval', explode(',', $chart1));
                     $returnArray[$j]['data'] = $last_year; 
                     $j++;           
  }
        
 
			return json_encode($returnArray);
		} 
		public function piechart()
		{
			ob_clean();
			global $wpdb;
                        $blog_id = 1;
	        $returnArray = array();
                    $imptype = array('Post','Page','Comments','Custom Post','Users','Eshop');
			$i = 0;
                   foreach($imptype as $imp) {
                         $OverviewDetails = $wpdb->get_results("select *  from smackcsv_pie_log where type = '{$imp}'  and value != 0");
             		foreach($OverviewDetails as  $overview){
			       $returnArray[$i][0] = $overview->type; 
				$returnArray[$i][1] = (int)$overview->value;
                              	$i++;
			} 
                        
            }
                  
                                if(empty($returnArray ) ){
                               $returnArray['label']  = 'No Imports Yet' ;
                                      }
			return json_encode($returnArray);
              
                       

		}
}
