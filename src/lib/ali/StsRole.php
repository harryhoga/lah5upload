<?php

namespace Encore\lah5upload\lib\ali;

// use Sts\Request\V20150401 as Sts;
use AlibabaCloud\Sts\Sts;
use PHPUnit\Framework\TestCase;
use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Sts\V20150401\AssumeRole;
use AlibabaCloud\Client\Exception\ServerException;
use AlibabaCloud\Client\Exception\ClientException;

class StsRole
{
    private $configOss;

    public function __construct($config)
    {
        $this->configOss = $config;
    }

    public function accessKey()
    {
        return $this->configOss['access_key'];
    }

    public function accessSecret()
    {
        return $this->configOss['access_secret'];
    }

    public function regionId()
    {
        return $this->configOss['sts_region_id'];
    }

    public function stsEndpoint()
    {
        return $this->configOss['sts_endpoint'];
    }

    public function policy()
    {
        return $this->configOss['policy'];
    }

    public function RoleSessionName()
    {
        return $this->configOss['RoleSessionName'];
    }
    public function RoleArn()
    {
        return $this->configOss['RoleArn'];
    }

    public function stsRam()
    {
        return $this->configOss['sts_ram'];
    }

    function getAssumeRole($userId, $type = 'public')
    {

        $access_key = $this->configOss['access_key'];
        $secret_key = $this->configOss['access_secret'];
        $region_id = $this->configOss['sts_region_id'];
        $endpoint = $this->configOss['sts_endpoint'];
        $policy =  $this->configOss['policy'];
        $RoleSessionName =  $this->configOss['RoleSessionName'];
        $RoleArn =  $this->configOss['sts_ram'];

        print_r($this->configOss);


        AlibabaCloud::accessKeyClient($access_key, $secret_key)
            ->regionId($region_id)
            ->asDefaultClient();
        try {
            $response = Sts::v20150401()
                ->assumeRole()
                ->withRoleArn($RoleArn) //指定角色ARN
                ->withRoleSessionName($RoleSessionName) //RoleSessionName即临时身份的会话名称，用于区分不同的临时身份
                //设置权限策略以进一步限制角色的权限
                ->withPolicy($policy)
                // ->withPolicy('{
                //   "Version": "1",
                //   "Statement": [
                //     {
                //       "Effect": "Allow",
                //       "Action": [
                //         "oss:*"
                //       ], 
                //         "acs:oss:*:*:*"
                //       ]
                //     }
                //   ]
                // }')
                ->connectTimeout(60)
                ->timeout(65)
                ->request();
            // $Credentials = (array) $response->Credentials;
            // $AssumedRoleUser = (array) $response->AssumedRoleUser;
            // return response()->json(['code' => 200, 'Credentials' =>  $Credentials, 'AssumedRoleUser' => $AssumedRoleUser]);
            return $response;
        } catch (Exception $e) {
            print_r($e);
            return null;
        }
    }

    function getReadAssumeRole($userId, $type = 'public')
    {
        include_once 'aliyun-php-sdk-core/Config.php';
        // 只允许子用户使用角色
        $region_id = $this->configOss['sts_region_id'];
        $endpoint = $this->configOss['sts_endpoint'];
        \DefaultProfile::addEndpoint($region_id, $region_id, "Sts", $endpoint);
        $iClientProfile = \DefaultProfile::getProfile($region_id, $this->configOss['access_key'], $this->configOss['access_secret']);
        $client = new \DefaultAcsClient($iClientProfile);
        // 角色资源描述符，在RAM的控制台的资源详情页上可以获取
        $roleArn = $this->configOss['sts_ram'];
        $bucket = $this->configOss['bucket'];
        if ($type == 'private') {
            $bucket = $this->configOss['bucket_private'];
        }
        $policy = <<<POLICY
        {
        "Statement": [
            {
                "Action": [
                    "oss:GetBucketAcl"
                ],
                "Effect": "Allow",
                "Resource": [
                    "acs:oss:*:*:$bucket/*"
                ]
            },
            {
                "Action": [
                    "oss:GetObject"
                ],
                "Effect": "Allow",
                "Resource":[
                    "acs:oss:*:*:$bucket/*"
                ]
            }
        ],
        "Version": "1"
        }
POLICY;
        $request = new Sts\AssumeRoleRequest();
        // RoleSessionName即临时身份的会话名称，用于区分不同的临时身份
        // 您可以使用您的客户的ID作为会话名称
        $request->setRoleSessionName("client_name" . $userId);
        $request->setRoleArn($roleArn);
        $request->setPolicy($policy);
        $request->setDurationSeconds(3600); //有效期(过期时间)15-60分钟
        try {
            $response = $client->getAcsResponse($request);
            return $response;
        } catch (ServerException $e) {
            print "Error: " . $e->getErrorCode() . " Message: " . $e->getMessage() . "\n";
        } catch (ClientException $e) {
            print "Error: " . $e->getErrorCode() . " Message: " . $e->getMessage() . "\n";
        }
    }
}
