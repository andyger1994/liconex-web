const fs=require('fs'),assert=require('assert');const {PhpNode}=require('./node_modules/php-wasm/PhpNode.js');
const src=fs.readFileSync('../theme/liconex-shop/store-improvements.php','utf8').replace(/^<\?php/,'');
const stub=`<?php define('ABSPATH','/');$filters=[];
 function add_action(...$args){}function add_filter($name,$fn,...$rest){global $filters;$filters[$name]=$fn;}
 $skus=[1=>'BAL430197',2=>'BAL430425',3=>'BAL430432',4=>'BAL541237',5=>'BAL320801',6=>'BAL860402',7=>'BAL312059',8=>'BAL508834',9=>'BAL160827',10=>'LX-KIT-8000-INST',11=>'LX-KIT-2018-INST',12=>'LX-KIT-NET24-INST',13=>'UNKNOWN'];
 class TestProduct{function __construct(public $sku){}function get_sku(){return $this->sku;}}
 function wc_get_product($id){global $skus;return new TestProduct($skus[$id]??'OTHER');}
 function wc_get_product_id_by_sku($sku){global $skus;return array_search($sku,$skus,true);}
 function has_term($term,$tax,$id){return $id===13;}
 `;
const tests=`
 $f=$filters['woocommerce_related_products'];
 if($f([99],1)!==[2,3])throw new Exception('8000 accessory mixing');
 if($f([99],4)!==[6,7,8,9])throw new Exception('SMART central mixing');
 if($f([99],10)!==[11,12])throw new Exception('kit comparison');
 if($f([99],13)!==[])throw new Exception('unknown compatibility');
 if($f([99],14)!==[99])throw new Exception('non alarm changed');
 $name=lx_readable_product_name('ADAPTADOR | HDMI | 4K@60HZ | AMT 8000 PRO');
 if($name!=='Adaptador · HDMI · 4K@60HZ · AMT 8000 PRO')throw new Exception('technical model altered');
 echo 'PASS model preservation; 8000 separation; SMART components; kit alternatives; unknown alarm isolation; non-alarm unchanged';
 `;
let out='';let p=new PhpNode();p.addEventListener('output',e=>out+=e.detail.join(''));p.run(stub+src+tests).then(code=>{console.log(out);assert(!code&&out.includes('PASS model'))});
