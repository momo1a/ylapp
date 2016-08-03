<?php
/**
 * TOP API: alibaba.aliqin.fc.sms.num.send request
 * 
 * @author auto create
 * @since 1.0, 2016.05.24
 */
class AlibabaAliqinFcSmsNumSendRequest
{
	/** 
	 * 公共回传参数，在“消息返回”中会透传回该参数；举例：用户可以传入自己下级的会员ID，在消息返回时，该会员ID会包含在内，用户可以根据该会员ID识别是哪位会员使用了你的应用
	 **/
	private $extend;
	
	/** 
	 * 短信接收号码。支持单个或多个手机号码，传入号码为11位手机号码，不能加0或+86。群发短信需传入多个号码，以英文逗号分隔，一次调用最多传入200个号码。示例：18600000000,13911111111,13322222222
	 **/
	private $recNum;
	
	/** 
	 * 短信签名，传入的短信签名必须是在阿里大鱼“管理中心-短信签名管理”中的可用签名。如“阿里大鱼”已在短信签名管理中通过审核，则可传入”阿里大鱼“（传参时去掉引号）作为短信签名。短信效果示例：【阿里大鱼】欢迎使用阿里大鱼服务。
	 **/
	private $smsFreeSignName;
	
	/** 
	 * 短信模板变量，传参规则{"key":"value"}，key的名字须和申请模板中的变量名一致，多个变量之间以逗号隔开。示例：针对模板“验证码${code}，您正在进行${product}身份验证，打死不要告诉别人哦！”，传参时需传入{"code":"1234","product":"alidayu"}
	 **/
	private $smsParam;
	
	/** 
	 * 短信模板ID，传入的模板必须是在阿里大鱼“管理中心-短信模板管理”中的可用模板。示例：SMS_585014
	 **/
	private $smsTemplateCode;
	
	/** 
	 * 短信类型，传入值请填写normal
	 **/
	private $smsType;
	
	private $apiParas = array();
	
	public function setExtend($extend)
	{
		$this->extend = $extend;
		$this->apiParas["extend"] = $extend;
	}

	public function getExtend()
	{
		return $this->extend;
	}

	public function setRecNum($recNum)
	{
		$this->recNum = $recNum;
		$this->apiParas["rec_num"] = $recNum;
	}

	public function getRecNum()
	{
		return $this->recNum;
	}

	public function setSmsFreeSignName($smsFreeSignName)
	{
		$this->smsFreeSignName = $smsFreeSignName;
		$this->apiParas["sms_free_sign_name"] = $smsFreeSignName;
	}

	public function getSmsFreeSignName()
	{
		return $this->smsFreeSignName;
	}

	public function setSmsParam($smsParam)
	{
		$this->smsParam = $smsParam;
		$this->apiParas["sms_param"] = $smsParam;
	}

	public function getSmsParam()
	{
		return $this->smsParam;
	}

	public function setSmsTemplateCode($smsTemplateCode)
	{
		$this->smsTemplateCode = $smsTemplateCode;
		$this->apiParas["sms_template_code"] = $smsTemplateCode;
	}

	public function getSmsTemplateCode()
	{
		return $this->smsTemplateCode;
	}

	public function setSmsType($smsType)
	{
		$this->smsType = $smsType;
		$this->apiParas["sms_type"] = $smsType;
	}

	public function getSmsType()
	{
		return $this->smsType;
	}

	public function getApiMethodName()
	{
		return "alibaba.aliqin.fc.sms.num.send";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->recNum,"recNum");
		RequestCheckUtil::checkNotNull($this->smsFreeSignName,"smsFreeSignName");
		RequestCheckUtil::checkNotNull($this->smsTemplateCode,"smsTemplateCode");
		RequestCheckUtil::checkNotNull($this->smsType,"smsType");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}


class RequestCheckUtil
{
    /**
     * У���ֶ� fieldName ��ֵ$value�ǿ�
     *
     **/
    public static function checkNotNull($value,$fieldName) {

        if(self::checkEmpty($value)){
            throw new Exception("client-check-error:Missing Required Arguments: " .$fieldName , 40);
        }
    }

    /**
     * �����ֶ�fieldName��ֵvalue �ĳ���
     *
     **/
    public static function checkMaxLength($value,$maxLength,$fieldName){
        if(!self::checkEmpty($value) && mb_strlen($value , "UTF-8") > $maxLength){
            throw new Exception("client-check-error:Invalid Arguments:the length of " .$fieldName . " can not be larger than " . $maxLength . "." , 41);
        }
    }

    /**
     * �����ֶ�fieldName��ֵvalue������б?��
     *
     **/
    public static function checkMaxListSize($value,$maxSize,$fieldName) {

        if(self::checkEmpty($value))
            return ;

        $list=preg_split("/,/",$value);
        if(count($list) > $maxSize){
            throw new Exception("client-check-error:Invalid Arguments:the listsize(the string split by \",\") of ". $fieldName . " must be less than " . $maxSize . " ." , 41);
        }
    }

    /**
     * �����ֶ�fieldName��ֵvalue �����ֵ
     *
     **/
    public static function checkMaxValue($value,$maxValue,$fieldName){

        if(self::checkEmpty($value))
            return ;

        self::checkNumeric($value,$fieldName);

        if($value > $maxValue){
            throw new Exception("client-check-error:Invalid Arguments:the value of " . $fieldName . " can not be larger than " . $maxValue ." ." , 41);
        }
    }

    /**
     * �����ֶ�fieldName��ֵvalue ����Сֵ
     *
     **/
    public static function checkMinValue($value,$minValue,$fieldName) {

        if(self::checkEmpty($value))
            return ;

        self::checkNumeric($value,$fieldName);

        if($value < $minValue){
            throw new Exception("client-check-error:Invalid Arguments:the value of " . $fieldName . " can not be less than " . $minValue . " ." , 41);
        }
    }

    /**
     * �����ֶ�fieldName��ֵvalue�Ƿ���number
     *
     **/
    protected static function checkNumeric($value,$fieldName) {
        if(!is_numeric($value))
            throw new Exception("client-check-error:Invalid Arguments:the value of " . $fieldName . " is not number : " . $value . " ." , 41);
    }

    /**
     * У��$value�Ƿ�ǿ�
     *  if not set ,return true;
     *	if is null , return true;
     *
     *
     **/
    public static function checkEmpty($value) {
        if(!isset($value))
            return true ;
        if($value === null )
            return true;
        if(is_array($value) && count($value) == 0)
            return true;
        if(is_string($value) &&trim($value) === "")
            return true;

        return false;
    }

}
