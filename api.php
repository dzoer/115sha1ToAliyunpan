<?php
/*
 *  115sha1转阿里云盘秒传码
 *  Author：OrzMiku
 *  Blog：https://www.ecy.pink
 */
 
 //导入mime type
 include 'mime_type.php'; 
 
 //获取文件mime type方法
 function getMimetype($var,$mt){
   return $mt[$var];
 }
 
 //获取115sha1
 //$txt = file_get_contents('115.txt');
 
 //获取数据，转化为数组格式
 $txt = json_decode(file_get_contents( "php://input"),true)['p115sha1'];

 $raw1 = explode("\n",$txt);
/* $xy = explode("://",$raw1[0]);
 
 if($xy[0] !== "115"){
   if($xy[0]=="aliyunpan"){
     die('您输入的为阿里云盘秒传码（第一个），请检查');
   }else{
     die('秒传码格式错误（第一个)');
 }
 
 */
 
 //遍历数组
 foreach($raw1 as $k => $v){
 
   //分割115sha1
   $raw2 = explode("|",$v);
   
   //获取文件名
   $result[$k]['name'] = explode("//",$raw2[0])[1];
   
   //获取sha1
   $result[$k]['sha1'] = $raw2[2];
   
   //获取文件大小
   $result[$k]['size'] = $raw2[1];
   
   //获取文件后缀，查询对应mime type
   $hz = end(explode('.',$result[$k]['name']));
   $result[$k]['mimeType'] = getMimetype($hz,$mime_types);
   
   $xy[$k] = explode("://",$raw2[0])[0];
   if($xy[$k] !== "115"){
     //如果不是115sha1
     if($xy[$k] == "aliyunpan"){ //不是115却是阿里云盘
      $ali[$k] = $v; //不转化也不报错
     }else{ //既不是115也不是阿里云盘
       $ali[$k] = "该115sha1格式错误"; 
     }
   }else{
   //合成阿里秒传码
   $ali[$k] = "aliyunpan://".$result[$k]['name']."|".$result[$k]['sha1']."|".$result[$k]['size']."|".$result[$k]['mimeType'];
   }
 }
 
 //打印结果
    foreach($ali as $k => $v){
      echo $v."\n";
    }