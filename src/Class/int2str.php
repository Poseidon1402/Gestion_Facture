<?php

  namespace App\Class;

  Class NumberToStr{
    
    public function intToStr(float $a , $mll = false, $mlld = false){
      $joakim = explode('.',$a);
      if (isset($joakim[1]) && $joakim[1]!=''){
        return $this->intToStr($joakim[0]).'virgule '.$this->intToStr($joakim[1]) ;
      }
      if ($a<0) return 'moins '.$this->intToStr(-$a);
      if ($a<17){
        switch ($a){
        case 0: return 'zero';
        case 1: return 'un';
        case 2: return 'deux';
        case 3: return 'trois';
        case 4: return 'quatre';
        case 5: return 'cinq';
        case 6: return 'six';
        case 7: return 'sept';
        case 8: return 'huit';
        case 9: return 'neuf';
        case 10: return 'dix';
        case 11: return 'onze';
        case 12: return 'douze';
        case 13: return 'treize';
        case 14: return 'quatorze';
        case 15: return 'quinze';
        case 16: return 'seize';
      }
      } else if ($a<20){
        return 'dix-'.$this->intToStr($a-10);
      } else if ($a<100){
      if ($a%10==0){
        switch ($a){
        case 20: return 'vingt';
        case 30: return 'trente';
        case 40: return 'quarante';
        case 50: return 'cinquante';
        case 60: return 'soixante';
        case 70: return 'soixante-dix';
        case 80: return 'quatre-vingt';
        case 90: return 'quatre-vingt-dix';
      }
      } elseif (substr($a, -1)==1){
        if( ((int)($a/10)*10)<70 ){
            return $this->intToStr((int)($a/10)*10).'-et-un';
      } elseif ($a==71) {
            return 'soixante-et-onze';
      } elseif ($a==81) {
        return 'quatre-vingt-un';
      } elseif ($a==91) {
        return 'quatre-vingt-onze';
      }
      } elseif ($a<70){
        return $this->intToStr($a-$a%10).'-'.$this->intToStr($a%10);
      } elseif ($a<80){
        return $this->intToStr(60).'-'.$this->intToStr($a%20);
      } else{
        return $this->intToStr(80).'-'.$this->intToStr($a%20);
      }

      } else if ($a==100){
        return 'cents';
      } else if ($a<200){
        return $this->intToStr(100).' '.$this->intToStr($a%100);
      } else if ($a<1000){
        if($a%100==0)
        return $this->intToStr((int)($a/100)).' '.$this->intToStr(100);
        if($a%100!=0)return $this->intToStr((int)($a/100)).' '.$this->intToStr(100).' '.$this->intToStr($a%100);
      
      } else if ($a==1000){
        return 'milles';
      } else if ($a<2000){
        return $this->intToStr(1000).' '.$this->intToStr($a%1000).' ';
      } else if ($a<1000000){
      //return $this->intToStr((int)($a/1000)).' '.$this->intToStr(1000).' '.$this->intToStr($a%1000);
      if($a%1000==0)
        return $this->intToStr((int)($a/1000)).' '.$this->intToStr(1000);
        if($a%1000!=0)return $this->intToStr((int)($a/1000)).' '.$this->intToStr(1000).' '.$this->intToStr($a%1000);

      }
      elseif($a==1000000 && $mll==false){
        return 'un million';
      }
      else if ($a==1000000){
        return 'millions';
      } else if ($a<2000000){
        return $this->intToStr(1000000).' '.$this->intToStr($a%1000000).' ';
      } else if ($a<1000000000){
       // return $this->intToStr((int)($a/1000000)).' '.$this->intToStr(1000000).' '.$this->intToStr($a%1000000);
        if($a%1000000==0)
        return $this->intToStr((int)($a/1000000), true).' '.$this->intToStr(1000000,true);
        if($a%1000000!=0)return $this->intToStr((int)($a/1000000), true).' '.$this->intToStr(1000000,true).' '.$this->intToStr($a%1000000,true);

      }
      elseif($a==1000000000 && $mlld==false){
        return 'un milliard';
      }
      else if ($a==1000000000){
        return 'milliards';
      } else if ($a<2000000000){
        return $this->intToStr(1000000000).' '.$this->intToStr($a%1000000000).' ';
      } else if ($a<1000000000000){
        //return $this->intToStr((int)($a/1000000000)).' '.$this->intToStr(1000000000).' '.$this->intToStr($a%1000000000);
        if($a%1000000000==0)
        return $this->intToStr((int)($a/1000000000),true,true).' '.$this->intToStr(1000000000,true,true);
        if($a%1000000000!=0)return $this->intToStr((int)($a/1000000000),true,true).' '.$this->intToStr(1000000000,true,true).' '.$this->intToStr($a%1000000000,true,true);
      }
      else if ($a>1000000000000){
        if($a%1000000000000==0)
        return $this->intToStr((int)($a/1000000000000)).' '.$this->intToStr(1000000000000);
        if($a%1000000000000!=0)return $this->intToStr((int)($a/1000000000000)).' '.$this->intToStr(1000000000000).' '.$this->intToStr($a%1000000000000);
      }
    }
  }