<?php

use User_model as User;

class Sysadmin extends Controller
{
    public function index()
    {
        redirect('sysadmin/login', true);
    }

    public function login()
    {
        $data = [
            'title' => 'System Admin',
            'currentSidebar' => 'login',
            'currentSubSidebar' => NULL,
            'token' => csrf_token()
        ];

        view('sysadmin/login', $data);
    }

    public function authorize()
    {
        if (validateCsrf() === true) {

            $user_name = escape($_POST['username']);
            $enteredPassword = escape($_POST['password']);

            $usernameCheck = $_ENV['SYSTEM_USER'];

            if ($usernameCheck == $user_name) {
                if ($_ENV['AUTH_TYPE'] == 'MySQL') {
                    $data = $this->authDB($user_name, $enteredPassword);
                } else if ($_ENV['AUTH_TYPE'] == 'LDAP') {

                    // $data = $this->authLDAP($user_name, $enteredPassword);
                    $data = [
                        'resCode' => 400,
                        'message' => "Login for LDAP is closed",
                    ];
                }
            } else {
                $data = [
                    'resCode' => 400,
                    'message' => "Invalid credentials",
                ];
            }
        } else {
            $data = [
                'resCode' => 'token',
                'message' => validateCsrf(),
            ];
        }

        json($data);
    }

    public function authDB($user_name, $enteredPassword)
    {
        $redirectUrl = NULL;

        $current_password = $_ENV['SYSTEM_PASSWORD'];
        $email = $_ENV['SYSTEM_EMAIL'];

        $result = passDecrypt($current_password, $enteredPassword);

        if ($result) {
            $this->session->set('userID', '0');
            $this->session->set('roleID', '0');
            $this->session->set('roleName', 'Owner');
            $this->session->set('companyID', '0');
            $this->session->set('companyName', 'CANTHINK SOLUTION');
            $this->session->set('userEmail', $email);
            $this->session->set('fullname', "SYSTEM ADMINISTRATOR");
            $this->session->set('preferredName', "SYSADMIN");
            $this->session->set('avatar', 'default/user.png');
            $this->session->set('isLoggedIn', TRUE);

            $response = 200;
            $message = 'Successfully Logged In';
            $redirectUrl = url('dashboard');
        } else {
            $response = 400;
            $message = 'Invalid credentials';
        }

        return $data = array(
            "resCode" => $response,
            "message" => $message,
            "redirectUrl" => $redirectUrl
        );
    }

    public function logout()
    {
        session()->clear();
        redirect('auth');
    }
}
