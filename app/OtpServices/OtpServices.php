<?php

class OtpServices {

    /**
     * Define the instance of class.
     * 
     * @return void
     */
    public function __construct() {

    }

    /**
     * Send otp to the user
     * 
     * @param  array $otp
     * 
     * @return void
     */
    public function sendOtp (array $data) {
        $username = $data['email'];
   
        $hash = config('app.local_hash');

        $test = "0";

        $sender = "TXTLCL"; 
        $numbers = "910000000000";
        $message = "This is a test message from the PHP API script.";
        // 612 chars or less
        // A single number or a comma-seperated list of numbers
        $message = urlencode($message);
        $data = "username=".$username."&hash=".$hash."&message=".$message."&sender=".$sender."&numbers=".$numbers."&test=".$test;
        $ch = curl_init('http://api.textlocal.in/send/?');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch); // This is the result from the API
        curl_close($ch);
    }
	
}

?>