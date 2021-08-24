<?php
//$json = file_get_contents('test1.json');
 $json = json_decode(file_get_contents( "php://input"),true)['json'];
 $json = json_decode($json,true);
 
 //导入mime type
 include 'mime_type.php'; 
 
 //获取文件mime type方法
 function getMimetype($var,$mt){
   return $mt[$var];
 }
 
 function toAli($files){//创建转换方法
   if($files !== NULL){
   foreach($files as $k => $v){
      //分割115sha1
      $raw = explode("|",$v);
      //获取文件后缀，查询对应mime type
      $hz = end(explode('.',$raw[0]));
      $name = $raw[0];
      $sha1 = $raw[2];
      $size = $raw[1];
      global $mime_types;
      $mt = getMimetype($hz,$mime_types);
      $ali[$k] = "aliyunpan://".$name."|".$sha1."|".$size."|".$mt;
   }
      return $ali;
   }
   
 }
 function scan($arr,$path = '/'){//扫描方法
   if(isset($arr['dir_name'])){
       $dpath = $path.$arr['dir_name'].'/';
       //$dir[$dpath]['files'] = $arr['files'];//储存文件
        $dir[$dpath]['ali'] = toAli($v['files']);//储存秒传码
      if(!empty($arr['dirs'])){//若有子目录
         $dir = $dir+scan($arr['dirs'],$dpath);//扫描子目录
       }
     return $dir;
   }else{
       if($arr !== NULL){foreach($arr as $k => $v){
         $dpath = $path.$v['dir_name'].'/';
         //$dir[$dpath]['files'] = $v['files'];//储存文件
         $dir[$dpath]['ali'] = toAli($v['files']);//储存秒传码
        if(!empty($arr['dirs'])){//若有子目录
           $dir = $dir+scan($v['dirs'],$dpath);//扫描子目录
         }
       }}
       return $dir;
   }
 }
   
 $dir = scan($json);
 //print_r($dir);
 if($dir !== NULL){
   echo "请勿直接使用油猴脚本导入！因为阿里秒传不支持目录结构！请按照路径创建好文件夹，在对应文件夹依次转存！";
 foreach($dir as $path => $ali){
   echo "\r\n\r\n\r\n路径：".$path."\r\n";
   echo "文件如下：\r\n";
   if($ali['ali'] !== NULL){
   foreach($ali['ali'] as $k => $v){
     echo $v."\r\n";
   }}
 }}
 