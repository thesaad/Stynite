<?php

class S3_lib
{
    public function __construct()
    {
        $ci = &get_instance();
        $ci->load->helper(array('constant'));
        require_once APPPATH . 'third_party/S3.php';
        //AWS access info

        //instantiate the class
        $s3 = new S3(AWS_ACCESS_KEY, AWS_SECRET_KEY);

      //  $s3->putBucket(AWS_BUCKET, S3::ACL_PUBLIC_READ);
    }
}

?>