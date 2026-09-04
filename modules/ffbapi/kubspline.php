<?php

//@author		Martin Kahlbacher, Gerald Musser 
//@email		gerald@musser.ws
//@copyright	Gerald Musser
//@year 		2009
//@version		0.1

class KUBSPLINE {
	
	private $a;
	private $b;
	private $c;
	private $y;
	private $xy;
	
	
	public function __construct($xy, $distance=10) {
		$numPunkte = count($xy);
		$size = $distance;

		//random points
		/*
		for($i=0;$i<=$numPunkte;$i++) {
			$tmp = array();
			$tmp[] = $size * $i; 
			$tmp[] = rand(-50,50);
			$xy[] = $tmp;
		}
		*/
		$xVal = 0;
		$yVal = 1;

		$xyLen = count($xy);
		$rs = array();


		for($i=1;$i<$xyLen-1;$i++) {
			$rs[$i] = 3*(  ($xy[$i+1][$yVal]-$xy[$i][$yVal]) / ($xy[$i+1][$xVal]-$xy[$i][$xVal])  - ($xy[$i][$yVal]-$xy[$i-1][$yVal]) / ($xy[$i][$xVal]-$xy[$i-1][$xVal])   );
		}

		$M = array(); //

		for($i=1; $i<$xyLen-1;$i++) {
			$tmp = array();
			$tmp[$i-1] = $xy[$i][$xVal] - $xy[$i-1][$xVal]; 
			$tmp[$i-1+1] = 2 * ($xy[$i+1][$xVal] - $xy[$i-1][$xVal]);
			$tmp[$i+1] = $xy[$i+1][$xVal] - $xy[$i][$xVal];
			$M[$i] = $tmp;
		}

		$A = $this->LU($M);

		$Z = array();
		$Z[1] = $rs[1];
		for($i=2;$i<=count($A);$i++) {
			$Z[$i] = $rs[$i] - $A[$i][$i-1] * $Z[$i-1];	
		}

		$B = array();
		if(count($A)>0)
			$B[count($A)] = $Z[count($Z)] / $A[count($A)][count($A)];
		else
			$B[count($A)] = 0;
		
		for($i=count($A)-1;$i>=1;$i--) {
			$tmp = $Z[$i];
			for($j=$i+1;$j<=count($A);$j++) {
				$tmp -= $A[$i][$j] * $B[$j];
			}
			$B[$i] = $tmp / $A[$i][$i]; 	
		}
		$B[0] = 0;
		$B[count($A)+1] = 0;

		$C = array();
		for($i=0;$i<=count($A);$i++) {
			$C[$i] = ($xy[$i+1][$yVal] - $xy[$i][$yVal]) / ($xy[$i+1][$xVal] - $xy[$i][$xVal]) - ($B[$i+1] - $B[$i]) * ($xy[$i+1][$xVal] - $xy[$i][$xVal]) / 3.0 - $B[$i] * ($xy[$i+1][$xVal] - $xy[$i][$xVal]); 
		}

		$AA = array();
		for($i=0;$i<=count($A);$i++) {
			$AA[$i] = ($B[$i+1]- $B[$i]) / (3.0 * ($xy[$i+1][$xVal] - $xy[$i][$xVal]) );
		}

  		$Y = array();
  		for($i=0;$i<count($xy)-1;$i++) {
  			for($j=$i*$size;$j<=($i+1)*$size-1;$j++) {
  				$Y[] = $AA[$i] * pow( ($j - $xy[$i][$xVal]), 3 ) + $B[$i] * pow( ($j - $xy[$i][$xVal]), 2 ) + $C[$i] * ($j - $xy[$i][$xVal]) + $xy[$i][$yVal];
  			}
		}
		$Y[] = $xy[count($xy)-1][$yVal];
		
		$this->a = $AA;
		$this->b = $B;
		$this->c = $C;
		$this->y = $Y;
		$this->xy = $xy;
		
		/*
 		$img = imagecreatetruecolor ( $size*($numPunkte-1), 100);
  		$gruenBG =  imagecolorallocate ( $img ,204 , 255 , 204);
  		$dunkelgruenBG =  imagecolorallocate ( $img ,35 , 110 , 35);
  		$yellowBG = imagecolorallocate ( $img ,240 , 240 , 20);
  		$redBg = imagecolorallocate ( $img ,240 , 0 , 0);
  		$schwarzText = imagecolorallocate ( $img ,0 , 0 , 0);
  		$rotText = imagecolorallocate ( $img ,210 , 0 , 0);
  		$darkblueBG = imagecolorallocate ( $img ,25 , 25 , 225);
  		$grayBG = imagecolorallocate ( $img ,230 , 225 , 225);
  		$darkGrayBG = imagecolorallocate ( $img ,100 , 100 , 100);
  		$someOrangeBG = imagecolorallocate ( $img ,255 , 128 , 0);
  		$whiteBG = imagecolorallocate ( $img ,255 , 255 , 255);
  		//$lightGreenBG = imagecolorallocate($img, 215,245,215);
  		$someKindBlueBG = imagecolorallocate ( $img ,175 , 240 , 240);
  		$filled = imagefill ( $img , 0 , 0 , $gruenBG );
  		//int imageline  ( resource $im  , int $x1  , int $y1  , int $x2  , int $y2  , int $col  )
  		
  		imageline($img, 0,50,1000,50, $schwarzText);

  		for($i=0;$i<$size*($numPunkte-1);$i+=$size) {
  			imageline($img, $i,0,$i,100, $whiteBG);
  		}

		
		for($i=1;$i<count($Y);$i++) {
			imageline($img, $i-1, 50-$Y[$i-1], $i, 50-$Y[$i], $redBg);
		}  		
  		
  		imagejpeg($img, "kubspline.jpg", 100);
  		*/
  		
  	}
	  
	  
	  
	public function getY() {
		return $this->y;
	}
	
	public function getXY() {
		return $this->xy;
	}	
 
	//gauss LU
	//@param matrix the matrix $M
	public function LU($matrix) {
		$matrixDim = count($matrix);
	
		$A = array();
		for($i=1;$i<=$matrixDim;$i++) {
			for($j=0;$j<=$matrixDim;$j++){
				$A[$i][$j] = 0;
			}
			$A[$i][$i-1] = $matrix[$i][$i-1];
			$A[$i][$i] =  $matrix[$i][$i];
			$A[$i][$i+1] = $matrix[$i][$i+1];
		}
	
	
		for($i = 1;$i<=$matrixDim;$i++) {
       // Bestimmen von R
       		for($j = $i;$j<=$matrixDim; $j++) {
           		for($k=1; $k<$i;$k++) {
           			$A[$i][$j] -=  $A[$i][$k] * $A[$k][$j]; 
           		}
       		}    
       // Bestimmen von L
       		for($j = $i+1;$j<=$matrixDim; $j++) {
           		for($k = 1; $k<$i;$k++) {
           			$A[$j][$i] -=  $A[$j][$k] * $A[$k][$i]; 
           		}
           		$A[$j][$i] /= $A[$i][$i];
     		}
		}
    
    	return $A;
    /*
    For i = 1 To n
       // Bestimmen von R
       For j = i To n
           For k = 1 To i-1               
               A(i,j) -= A(i,k) * A(k,j) 
           end
       end    
       // Bestimmen von L
       For j = i+1 To n
           For k = 1 To i-1
               A(j,i) -= A(j,k) * A(k,i)
           end
           A(j,i) /= A(i,i)
       end
    end
	*/
	}
}
?>