<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tooth extends Model
{
  public function teeth_convert(){
    $tooth= substr($this->teeth_name,strpos($this->teeth_name,'{'));
    $pos="";
    if ($tooth == "{{A}}" || $tooth == "{{B}}" || $tooth == "{{C}}"|| $tooth == "{{D}}"|| $tooth == "{{E}}") {
      $pos= "Upper Right ";
    }else if ($tooth == "{{F}}" || $tooth == "{{G}}" || $tooth == "{{H}}"|| $tooth == "{{I}}"|| $tooth == "{{J}}") {
      $pos= "Upper Left ";
    }else if ($tooth == "{{K}}" || $tooth == "{{L}}" || $tooth == "{{M}}"|| $tooth == "{{N}}"|| $tooth == "{{O}}") {
      $pos= "Lower Left ";
    }else if ($tooth == "{{P}}" || $tooth == "{{Q}}" || $tooth == "{{R}}"|| $tooth == "{{S}}"|| $tooth == "{{T}}") {
      $pos= "Lower Right ";
    }
    if ($tooth == "{{1}}" || $tooth == "{{16}}" || $tooth == "{{32}}"|| $tooth == "{{17}}") {
      $tooth='{{8}}';
    }else if ($tooth == "{{2}}" || $tooth == "{{15}}" || $tooth == "{{31}}"|| $tooth == "{{18}}"){
      $tooth= '{{7}}';
    }else if ($tooth == "{{3}}" || $tooth == "{{14}}" || $tooth == "{{30}}"|| $tooth == "{{19}}"){
      $tooth= '{{6}}';
    }else if ($tooth == "{{4}}" || $tooth == "{{13}}" || $tooth == "{{29}}"|| $tooth == "{{20}}"){
      $tooth= '{{5}}';
    }else if ($tooth == "{{5}}" || $tooth == "{{12}}" || $tooth == "{{28}}"|| $tooth == "{{21}}"){
      $tooth= '{{4}}';
    }else if ($tooth == "{{6}}" || $tooth == "{{11}}" || $tooth == "{{27}}"|| $tooth == "{{22}}"){
      $tooth= '{{3}}';
    }else if ($tooth == "{{7}}" || $tooth == "{{10}}" || $tooth == "{{26}}"|| $tooth == "{{23}}"){
      $tooth= '{{2}}';
    }else if ($tooth == "{{8}}" || $tooth == "{{9}}" || $tooth == "{{25}}"|| $tooth == "{{24}}"){
      $tooth= '{{1}}';
    }else if ($tooth == "{{A}}" || $tooth == "{{J}}" || $tooth == "{{T}}"|| $tooth == "{{K}}"){
      $tooth= '{{E}}';
    }else if ($tooth == "{{B}}" || $tooth == "{{I}}" || $tooth == "{{S}}"|| $tooth == "{{L}}"){
      $tooth= '{{D}}';
    }else if ($tooth == "{{C}}" || $tooth == "{{H}}" || $tooth == "{{R}}"|| $tooth == "{{M}}"){
      $tooth= '{{C}}';
    }else if ($tooth == "{{D}}" || $tooth == "{{G}}" || $tooth == "{{Q}}"|| $tooth == "{{N}}"){
      $tooth= '{{B}}';
    }else if ($tooth == "{{E}}" || $tooth == "{{F}}" || $tooth == "{{P}}"|| $tooth == "{{O}}"){
      $tooth= '{{A}}';
    }

    return $pos.$tooth;
  }
}
