const fs=require('fs'),assert=require('assert');const {PhpNode}=require('./node_modules/php-wasm/PhpNode.js');
const src=fs.readFileSync('../theme/liconex-shop/measurement.php','utf8').replace(/^<\?php/,'');
const stub=`<?php
 define('ABSPATH','/');$hooks=[];$events=[];$scenario='pending';
 function add_action($name,$fn,...$rest){global $hooks;$hooks[$name]=$fn;}
 function current_user_can($c){return false;}
 function wp_enqueue_script(...$args){}
 function wp_add_inline_script($handle,$code,...$args){global $events;if($handle==='lx-measurement')$events[]=json_decode(substr($code,strlen('window.lxCommerceEvents='),-1),true);}
 function wp_json_encode($v){return json_encode($v);}function get_template_directory_uri(){return '/theme';}
 function is_product(){return false;}function is_cart(){return false;}function is_checkout(){return true;}function is_order_received_page(){return true;}
 function get_query_var($q){return 2186;}function absint($n){return abs((int)$n);}function wc_clean($s){return $s;}function wp_unslash($s){return $s;}
 function WC(){return (object)['session'=>null];}
 class TestOrder {function get_order_key(){return 'secret';}function is_paid(){global $scenario;return $scenario==='paid';}function get_items(){return [];}function get_id(){return 2186;}function get_currency(){return 'USD';}function get_total(){return 110;}function get_total_tax(){return 5;}function get_shipping_total(){return 10;}}
 function wc_get_order($id){return new TestOrder;}
 `;
const test=`
 $_GET['key']='secret';$hooks['wp_enqueue_scripts']();if(count($events[0])!==0)throw new Exception('pending reported');
 $scenario='paid';$_GET['key']='wrong';$hooks['wp_enqueue_scripts']();if(count($events[1])!==0)throw new Exception('bad key reported');
 $_GET['key']='secret';$hooks['wp_enqueue_scripts']();if($events[2][0]['data']['transaction_id']!=='LX-2186'||$events[2][0]['data']['value']!=95)throw new Exception('paid payload');
 echo 'PASS pending excluded; wrong order key excluded; paid revenue excludes tax and shipping';
 `;
let out='';const php=new PhpNode();php.addEventListener('output',e=>out+=e.detail.join(''));php.run(stub+src+test).then(code=>{console.log(out);assert(!code&&out.includes('PASS pending'))});
