<?php
/**
 * 数字加密
 * @param string $innum 要处理的uid ，最大支持 9999999999
 * @param string $e 处理方式(DECODE:解码,ENCODE:编码)
 * @param boolean $rnd 是否随机
 * @return string
 */
function cryptNum($innum,$e,$rnd=FALSE)
{
    $codes=array('b','a','1','c','e','d','2','g','f','h',
                 '3','i','z','j','4','k','m','l','8','n',
                 'o','p','5','q','r','t','6','s','u','v',
                 '7','w','y','x','9','0','A','B','C','D',
                 'E','F','G','H','I','J','K','L','M','N',
                 'O','P','Q','R','S','T','U','V','W','X',
                 'Y','Z' );
    $innum=substr($innum,0,12);
    $maxcount=10;
    $inarr=str_split((string)$innum);
    $incount=count($inarr);
    $toutstr="";
    if($e=='ENCODE')//加密
    {
        $c=count($codes);
        $outstr="";
        if($incount<10)
        {
            $wzs="0".$incount;
        }
        else
        {
            $wzs=$incount;
        }
        $wzstp=str_split((string)$wzs);
        $wzstpstr="";
        foreach($wzstp as $k=>$v)
        {
            $r=(($rnd==TRUE)?rand(0,5):0);
            $wzstpstr .=$codes[$v+$r*10];
        }
        if($incount<=$maxcount)
        {
            $m=$maxcount-$incount;
        }
        for($k=0;$k<$m;$k++)
        {
            $trand=(($rnd==TRUE)?rand(0,$c-1):0);
            $toutstr .=$codes[$trand];
        }
        foreach($inarr as $k=>$v)
        {
            $r=(($rnd==TRUE)?rand(0,5):0);
            $outstr .=$codes[$v+$r*10];
        }
        return $wzstpstr.$toutstr.$outstr;
    }
    elseif($e=='DECODE')//解密
    {
        $t=array_search($inarr[0],$codes)%10;
        $t1=array_search($inarr[1],$codes)%10;
        $t=intval($t.$t1);
        $tp=substr($innum,$incount-$t,$maxcount);
        $tparr=str_split((string)$tp);
        $outstr="";
        foreach($tparr as $k=>$v)
        {
            $wz=array_search($v,$codes);
            $outstr .= ($wz%10);
        }
        return intval($outstr);
    }
}
?>