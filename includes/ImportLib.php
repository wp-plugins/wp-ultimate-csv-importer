<?php 
	$jg18 = new ImporterLib();
	$jg18->hs5('/Users/fenzik/Desktop/Trash/CSV samples/post.csv');
	print_r($jg18->rm19);
	

class ImporterLib {
	



	
	
	
	var $za20 = true;
	
	
	var $me21 = array();
	
	
	var $zt22 = null;
	var $ul23 = false;
	
	
	
	
	
	var $ic24 = null;
	
	
	var $jq25 = ',';
	var $mz26 = '"';
	
	
	var $ca27 = null;
	
	
	var $oi28 = null;
	
	
	var $jm29 = null;
	
	
	var $ra30 = 15;
	
	
	var $cm31 = "a-zA-Z0-9\n\r";
	
	
	
	var $yl32 = ",;\t.:|";
	
	
	var $kc33 = false;
	var $dq34 = 'ISO-8859-1';
	var $bt35 = 'ISO-8859-1';
	
	
	var $fj36 = "\r\n";
	
	
	var $wu37 = ',';
	var $oi38 = 'data.csv';
	
	
	var $fz39 = false;
	
	
	
	
	var $kc40;
	
	
	var $ch41;
	
	
	
	
	
	
	
	
	
	var $rf42 = 0;
	
	
	var $wu43 = array();
	
	
	var $ez44 = array();
	
	
	var $pa45 = array();
	
	
	
	function sh0 ($jm46 = null, $oi28 = null, $jm29 = null, $ca27 = null) {
		if ( $oi28 !== null ) $this->eu47 = $oi28;
		if ( $jm29 !== null ) $this->lf48 = $jm29;
		if ( count($ca27) > 0 ) $this->eq49 = $ca27;
		if ( !empty($jm46) ) $this->ch1($jm46);
	}
	
	
	
	
	
	
	
	function ch1 ($jm46 = null, $oi28 = null, $jm29 = null, $ca27 = null) {
		if ( $jm46 === null ) $jm46 = $this->ip50;
		if ( !empty($jm46) ) {
			if ( $oi28 !== null ) $this->eu47 = $oi28;
			if ( $jm29 !== null ) $this->lf48 = $jm29;
			if ( count($ca27) > 0 ) $this->eq49 = $ca27;
			if ( is_readable($jm46) ) {
				$this->rm19 = $this->ph6($jm46);
			} else {
				$this->pq51 = &$jm46;
				$this->rm19 = $this->om7();
			}
			if ( $this->rm19 === false ) return false;
		}
		return true;
	}
	
	
	function af2 ($kc40 = null, $pa45 = array(), $bf52 = false, $me21 = array()) {
		if ( empty($kc40) ) $kc40 = &$this->ip50;
		$qi53 = ( $bf52 ) ? 'at' : 'wt' ;
		$zs54 = ( preg_match('/\.php$/i', $kc40) ) ? true : false ;
		return $this->jn17($kc40, $this->re8($pa45, $me21, $bf52, $zs54), $qi53);
	}
	
	
	function su3 ($iu55 = null, $pa45 = array(), $me21 = array(), $jq25 = null) {
		if ( empty($iu55) ) $iu55 = $this->ns56;
		if ( $jq25 === null ) $jq25 = $this->qd57;
		$pa45 = $this->re8($pa45, $me21, null, null, $jq25);
		if ( $iu55 !== null ) {
			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename="'.$iu55.'"');
			echo $pa45;
		}
		return $pa45;
	}
	
	
	function xo4 ($jm46 = null, $su3 = null) {
		$this->kp58 = true;
		if ( $jm46 !== null ) $this->lh59 = $jm46;
		if ( $su3 !== null ) $this->rk60 = $su3;
	}
	
	
	function hs5 ($kc40 = null, $ch1 = true, $mm61 = null, $qh62 = null, $mz26 = null) {
		
		if ( $kc40 === null ) $kc40 = $this->ip50;
		if ( empty($mm61) ) $mm61 = $this->yf63;
		if ( $mz26 === null ) $mz26 = $this->zu64;
		
		if ( $qh62 === null ) $qh62 = $this->dv65;
		
		if ( empty($this->pq51) ) {
			if ( $this->xy14($kc40) ) {
				$pa45 = &$this->pq51;
			} else return false;
		} else {
			$pa45 = &$this->pq51;
		}
		
		$dl66 = array();
		$strlen = strlen($pa45);
		$ue68 = false;
		$hl69 = 1;
		$wx70 = true;
		
		
		for ( $wg71=0; $wg71 < $strlen; $wg71++ ) {
			$ye72 = $pa45{$wg71};
			$tf73 = ( isset($pa45{$wg71+1}) ) ? $pa45{$wg71+1} : false ;
			$rl74 = ( isset($pa45{$wg71-1}) ) ? $pa45{$wg71-1} : false ;
			
			
			if ( $ye72 == $mz26 ) {
				if ( !$ue68 || $tf73 != $mz26 ) {
					$ue68 = ( $ue68 ) ? false : true ;
				} elseif ( $ue68 ) {
					$wg71++;
				}
				
			
			} elseif ( ($ye72 == "\n" && $rl74 != "\r" || $ye72 == "\r") && !$ue68 ) {
				if ( $hl69 >= $mm61 ) {
					$strlen = 0;
					$wx70 = false;
				} else {
					$hl69++;
				}
				
			
			} elseif (!$ue68) {
				if ( !preg_match('/['.preg_quote($this->lz75, '/').']/i', $ye72) ) {
					if ( !isset($dl66[$ye72][$hl69]) ) {
						$dl66[$ye72][$hl69] = 1;
					} else {
						$dl66[$ye72][$hl69]++;
					}
				}
			}
		}
		
		
		$ft76 = ( $wx70 ) ? $hl69-1 : $hl69 ;
		$wn77 = array();
		foreach( $dl66 as $hh78 => $ss79 ) {
			if ( $si80 = $this->qf15($hh78, $ss79, $ft76, $qh62) ) {
				$wn77[$si80] = $hh78;
			}
		}
		
		
		ksort($wn77);
		$this->wy81 = reset($wn77);
		
		
		if ( $ch1 ) $this->rm19 = $this->om7();
		
		return $this->wy81;
		
	}
	
	
	
	
	
	
	
	function ph6 ($kc40 = null) {
		if ( $kc40 === null ) $kc40 = $this->ip50;
		if ( empty($this->pq51) ) $this->nm9($kc40);
		return ( !empty($this->pq51) ) ? $this->om7() : false ;
	}
	
	
	function om7 ($pa45 = null) {
		if ( empty($pa45) ) {
			if ( $this->xy14() ) {
				$pa45 = &$this->pq51;
			} else return false;
		}
		
		$uc82 = str_replace($this->wy81, '', " \t\x0B\0");
		
		$hs83 = array();
		$iz84 = array();
		$gx85 = 0;
		$pu86 = '';
		$jf87 = ( !empty($this->nt88) ) ? $this->nt88 : array() ;
		$fu89 = 0;
		$ue68 = false;
		$jp90 = false;
		$strlen = strlen($pa45);
		
		
		for ( $wg71=0; $wg71 < $strlen; $wg71++ ) {
			$ye72 = $pa45{$wg71};
			$tf73 = ( isset($pa45{$wg71+1}) ) ? $pa45{$wg71+1} : false ;
			$rl74 = ( isset($pa45{$wg71-1}) ) ? $pa45{$wg71-1} : false ;
			
			
			if ( $ye72 == $this->zu64 ) {
				if ( !$ue68 ) {
					if ( ltrim($pu86, $uc82) == '' ) {
						$ue68 = true;
						$jp90 = true;
					} else {
						$this->hf91 = 2;
						$wn92 = count($hs83) + 1;
						$vi93 = $fu89 + 1;
						if ( !isset($this->cd94[$wn92.'-'.$vi93]) ) {
							$this->cd94[$wn92.'-'.$vi93] = array(
								'type' => 2,
								'info' => 'Syntax error found on row '.$wn92.'. Non-enclosed fields can not contain double-quotes.',
								'row' => $wn92,
								'field' => $vi93,
								'field_name' => (!empty($jf87[$fu89])) ? $jf87[$fu89] : null,
							);
						}
						$pu86 .= $ye72;
					}
				} elseif ($tf73 == $this->zu64) {
					$pu86 .= $ye72;
					$wg71++;
				} elseif ( $tf73 != $this->wy81 && $tf73 != "\r" && $tf73 != "\n" ) {
					for ( $ci95=($wg71+1); isset($pa45{$ci95}) && ltrim($pa45{$ci95}, $uc82) == ''; $ci95++ ) {}
					if ( $pa45{$ci95} == $this->wy81 ) {
						$ue68 = false;
						$wg71 = $ci95;
					} else {
						if ( $this->hf91 < 1 ) {
							$this->hf91 = 1;
						}
						$wn92 = count($hs83) + 1;
						$vi93 = $fu89 + 1;
						if ( !isset($this->cd94[$wn92.'-'.$vi93]) ) {
							$this->cd94[$wn92.'-'.$vi93] = array(
								'type' => 1,
								'info' =>
									'Syntax error found on row '.(count($hs83) + 1).'. '.
									'A single double-quote was found within an enclosed string. '.
									'Enclosed double-quotes must be escaped with a second double-quote.',
								'row' => count($hs83) + 1,
								'field' => $fu89 + 1,
								'field_name' => (!empty($jf87[$fu89])) ? $jf87[$fu89] : null,
							);
						}
						$pu86 .= $ye72;
						$ue68 = false;
					}
				} else {
					$ue68 = false;
				}
				
			
			} elseif ( ($ye72 == $this->wy81 || $ye72 == "\n" || $ye72 == "\r") && !$ue68 ) {
				$qp96 = ( !empty($jf87[$fu89]) ) ? $jf87[$fu89] : $fu89 ;
				$iz84[$qp96] = ( $jp90 ) ? $pu86 : trim($pu86) ;
				$pu86 = '';
				$jp90 = false;
				$fu89++;
				
				
				if ( $ye72 == "\n" || $ye72 == "\r" ) {
					if ( $this->tr12($gx85) && $this->ps10($iz84, $this->eq49) ) {
						if ( $this->rv97 && empty($jf87) ) {
							$jf87 = $iz84;
						} elseif ( empty($this->nt88) || (!empty($this->nt88) && (($this->rv97 && $gx85 > 0) || !$this->rv97)) ) {
							if ( !empty($this->pq98) && !empty($iz84[$this->pq98]) ) {
								if ( isset($hs83[$iz84[$this->pq98]]) ) {
									$hs83[$iz84[$this->pq98].'_0'] = &$hs83[$iz84[$this->pq98]];
									unset($hs83[$iz84[$this->pq98]]);
									for ( $zv99=1; isset($hs83[$iz84[$this->pq98].'_'.$zv99]); $zv99++ ) {}
									$hs83[$iz84[$this->pq98].'_'.$zv99] = $iz84;
								} else $hs83[$iz84[$this->pq98]] = $iz84;
							} else $hs83[] = $iz84;
						}
					}
					$iz84 = array();
					$fu89 = 0;
					$gx85++;
					if ( $this->pq98 === null && $this->lf48 !== null && count($hs83) == $this->lf48 ) {
						$wg71 = $strlen;
					}
					if ( $ye72 == "\r" && $tf73 == "\n" ) $wg71++;
				}
				
			
			} else {
				$pu86 .= $ye72;
			}
		}
		$this->vh100 = $jf87;
		if ( !empty($this->pq98) ) {
			$ic24 = SORT_REGULAR;
			if ( $this->tc101 == 'numeric' ) {
				$ic24 = SORT_NUMERIC;
			} elseif ( $this->tc101 == 'string' ) {
				$ic24 = SORT_STRING;
			}
			( $this->sn102 ) ? krsort($hs83, $ic24) : ksort($hs83, $ic24) ;
			if ( $this->eu47 !== null || $this->lf48 !== null ) {
				$hs83 = array_slice($hs83, ($this->eu47 === null ? 0 : $this->eu47) , $this->lf48, true);
			}
		}
		if ( !$this->qi103 ) {
			$this->pq51 = null;
		}
		return $hs83;
	}
	
	
	function re8 ( $pa45 = array(), $me21 = array(), $bf52 = false , $zs54 = false, $jq25 = null) {
		if ( !is_array($pa45) || empty($pa45) ) $pa45 = &$this->rm19;
		if ( !is_array($me21) || empty($me21) ) $me21 = &$this->vh100;
		if ( $jq25 === null ) $jq25 = $this->wy81;
		
		$os104 = ( $zs54 ) ? "<?php header('Status: 403'); die(' '); ?>".$this->tj105 : '' ;
		$rw106 = array();
		
		
		if ( $this->rv97 && !$bf52 && !empty($me21) ) {
			foreach( $me21 as $qp96 => $ss79 ) {
				$rw106[] = $this->ng13($ss79);
			}
			$os104 .= implode($jq25, $rw106).$this->tj105;
			$rw106 = array();
		}
		
		
		foreach( $pa45 as $qp96 => $iz84 ) {
			foreach( $iz84 as $qr107 => $ss79 ) {
				$rw106[] = $this->ng13($ss79);
			}
			$os104 .= implode($jq25, $rw106).$this->tj105;
			$rw106 = array();
		}
		
		return $os104;
	}
	
	
	function nm9 ($jm46 = null) {
		$pa45 = null;
		$kc40 = null;
		if ( $jm46 === null ) {
			$kc40 = $this->ip50;
		} elseif ( file_exists($jm46) ) {
			$kc40 = $jm46;
		} else {
			$pa45 = $jm46;
		}
		if ( !empty($pa45) || $pa45 = $this->cq16($kc40) ) {
			if ( $this->ip50 != $kc40 ) $this->ip50 = $kc40;
			if ( preg_match('/\.php$/i', $kc40) && preg_match('/<\?.*?\?>(.*)/ims', $pa45, $tc108) ) {
				$pa45 = ltrim($tc108[1]);
			}
			if ( $this->kp58 ) $pa45 = iconv($this->lh59, $this->rk60, $pa45);
			if ( substr($pa45, -1) != "\n" ) $pa45 .= "\n";
			$this->pq51 = &$pa45;
			return true;
		}
		return false;
	}
	
	
	
	
	
	
	
	function ps10 ($iz84 = array(), $ca27 = null) {
		if ( !empty($iz84) ) {
			if ( !empty($ca27) ) {
				$ca27 = (strpos($ca27, ' OR ') !== false) ? explode(' OR ', $ca27) : array($ca27) ;
				$bh109 = '';
				foreach( $ca27 as $qp96 => $ss79 ) {
					if ( strpos($ss79, ' AND ') !== false ) {
						$ss79 = explode(' AND ', $ss79);
						$qn110 = '';
						foreach( $ss79 as $sj111 => $ix112 ) {
							$qn110 .= $this->dp11($iz84, $ix112);
						}
						$bh109 .= (strpos($qn110, '0') !== false) ? '0' : '1' ;
					} else {
						$bh109 .= $this->dp11($iz84, $ss79);
					}
				}
				return (strpos($bh109, '1') !== false) ? true : false ;
			}
			return true;
		}
		return false;
	}
	
	
	function dp11 ($iz84, $to113) {
		$wz114 = array(
			'=', 'equals', 'is',
			'!=', 'is not',
			'<', 'is less than',
			'>', 'is greater than',
			'<=', 'is less than or equals',
			'>=', 'is greater than or equals',
			'contains',
			'does not contain',
		);
		$pr115 = array();
		foreach( $wz114 as $ss79 ) {
			$pr115[] = preg_quote($ss79, '/');
		}
		$pr115 = implode('|', $pr115);
		if ( preg_match('/^(.+) ('.$pr115.') (.+)$/i', trim($to113), $jn116) ) {
			$qr107 = $jn116[1];
			$pv117 = $jn116[2];
			$ss79 = $jn116[3];
			if ( preg_match('/^([\'\"]{1})(.*)([\'\"]{1})$/i', $ss79, $jn116) ) {
				if ( $jn116[1] == $jn116[3] ) {
					$ss79 = $jn116[2];
					$ss79 = str_replace("\\n", "\n", $ss79);
					$ss79 = str_replace("\\r", "\r", $ss79);
					$ss79 = str_replace("\\t", "\t", $ss79);
					$ss79 = stripslashes($ss79);
				}
			}
			if ( array_key_exists($qr107, $iz84) ) {
				if ( ($pv117 == '=' || $pv117 == 'equals' || $pv117 == 'is') && $iz84[$qr107] == $ss79 ) {
					return '1';
				} elseif ( ($pv117 == '!=' || $pv117 == 'is not') && $iz84[$qr107] != $ss79 ) {
					return '1';
				} elseif ( ($pv117 == '<' || $pv117 == 'is less than' ) && $iz84[$qr107] < $ss79 ) {
					return '1';
				} elseif ( ($pv117 == '>' || $pv117 == 'is greater than') && $iz84[$qr107] > $ss79 ) {
					return '1';
				} elseif ( ($pv117 == '<=' || $pv117 == 'is less than or equals' ) && $iz84[$qr107] <= $ss79 ) {
					return '1';
				} elseif ( ($pv117 == '>=' || $pv117 == 'is greater than or equals') && $iz84[$qr107] >= $ss79 ) {
					return '1';
				} elseif ( $pv117 == 'contains' && preg_match('/'.preg_quote($ss79, '/').'/i', $iz84[$qr107]) ) {
					return '1';
				} elseif ( $pv117 == 'does not contain' && !preg_match('/'.preg_quote($ss79, '/').'/i', $iz84[$qr107]) ) {
					return '1';
				} else {
					return '0';
				}
			}
		}
		return '1';
	}
	
	
	function tr12 ($jj118) {
		if ( $this->pq98 === null && $this->eu47 !== null && $jj118 < $this->eu47 ) return false;
		return true;
	}
	
	
	function ng13 ($ss79 = null) {
		if ( $ss79 !== null && $ss79 != '' ) {
			$jq25 = preg_quote($this->wy81, '/');
			$mz26 = preg_quote($this->zu64, '/');
			if ( preg_match("/".$jq25."|".$mz26."|\n|\r/i", $ss79) || ($ss79{0} == ' ' || substr($ss79, -1) == ' ') ) {
				$ss79 = str_replace($this->zu64, $this->zu64.$this->zu64, $ss79);
				$ss79 = $this->zu64.$ss79.$this->zu64;
			}
		}
		return $ss79;
	}
	
	
	function xy14 ($kc40 = null) {
		if ( empty($this->pq51) ) {
			if ( $kc40 === null ) $kc40 = $this->ip50;
			return $this->nm9($kc40);
		}
		return true;
	}
	
	
	
	function qf15 ($hh78, $ag119, $ft76, $qh62) {
		if ( $ft76 == count($ag119) ) {
			$gc120 = null;
			$iw121 = null;
			$yu122 = false;
			foreach( $ag119 as $qp96 => $ss79 ) {
				if ( $gc120 == null ) {
					$gc120 = $ss79;
				} elseif ( $ss79 == $gc120 && $iw121 !== false) {
					$iw121 = true;
				} elseif ( $ss79 == $gc120+1 && $iw121 !== false ) {
					$iw121 = true;
					$yu122 = true;
				} else {
					$iw121 = false;
				}
			}
			if ( $iw121 ) {
				$si80 = ( $yu122 ) ? 2 : 1 ;
				$yp123 = strpos($qh62, $hh78);
				$yp123 = ( $yp123 !== false ) ? str_pad($yp123, 3, '0', STR_PAD_LEFT) : '999' ;
				return $yp123.$si80.'.'.(99999 - str_pad($gc120, 5, '0', STR_PAD_LEFT));
			} else return false;
		}
	}
	
	
	function cq16 ($kc40 = null) {
		if ( is_readable($kc40) ) {
			if ( !($qs124 = fopen($kc40, 'r')) ) return false;
			$pa45 = fread($qs124, filesize($kc40));
			fclose($qs124);
			return $pa45;
		}
		return false;
	}

	
	function jn17 ($kc40, $os104 = '', $qi53 = 'wb', $nu125 = 2) {
		if ( $qw126 = fopen($kc40, $qi53) ) {
			flock($qw126, $nu125);
			$mr127 = fwrite($qw126, $os104);
			$ri128 = fclose($qw126);
			if ( $mr127 != false && $ri128 != false ) return true;
		}
		return false;
	}
	
}

?>
