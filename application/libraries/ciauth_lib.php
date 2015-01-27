<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * authorization library
 */

class Ciauth_lib {
    
    /**
     * CI global 
     */
    private $ci;
    
    /**
     * auth model
     * 
     * @property Ciauth_model $auth_model 
     */
    protected $auth_model;
    
    protected $pcFilePath = 'password.php';

    private $defaultGroup;
    private $hashMethod;
    private $saltLength;
    
    /**
     * Error message container
     * @var array 
     */
    private $errors = array();
    
    /**
     * Messages
     * @var array
     */
    private $messages = array();
    
    /**
     * @var string
     */
    protected $error_start_delimiter;
    
    /**
     * @var string
     */
    protected $error_end_delimiter;

    /**
     * @var string
     */
    protected $message_start_delimeter;
    
    /**
     * @var string
     */
    protected $message_end_delimeter;
    
    protected $gender;

    /**
     * __construct
     * 
     * @return void
     */
    public function __construct() 
    {
        // password_compat library
        $pcFilePath = APPPATH . 'libraries/' . rtrim($this->pcFilePath, '/');
        if (file_exists($pcFilePath)) {
            require_once $pcFilePath;
        }
        $this->ci =& get_instance(); 
        
        $this->ci->load->config('ciauth', TRUE);
        $this->ci->load->helper(array('cookie', 'url'));
        $this->ci->load->library(array('session'));
        
        $this->ci->load->model('ciauth_model', 'auth_model');
        
        $this->auth_model = $this->ci->auth_model;
        
        $this->defaultGroup            = $this->ci->config->item('default_group', 'ciauth');
        $this->hashMethod              = $this->ci->config->item('hash_method', 'ciauth');
        $this->saltLength              = $this->ci->config->item('salt_length', 'ciauth');
        
        $this->message_start_delimiter = $this->ci->config->item('message_start_delimiter', 'ciauth');
        $this->message_end_delimiter   = $this->ci->config->item('message_end_delimiter', 'ciauth');
        $this->error_start_delimiter   = $this->ci->config->item('error_start_delimiter', 'ciauth');
        $this->error_end_delimiter     = $this->ci->config->item('error_end_delimiter', 'ciauth');
        
        $this->gender                  = array_keys($this->ci->config->item('gender', 'ciauth'));
    }
    
    /**
     * check username for duplicate
     * 
     * @param string $str
     * @return boolean
     */
    public function userNameCheck($str)
    {
        $this->ci->form_validation->set_message('external_callbacks', 'Error, user name exists');
        return !$this->ci->auth_model->userNameCheck($str);
    }
    
    /**
     * check date
     * 
     * @param string $str mysql date
     * @return boolean
     */
    public function dateCheck($str)
    {
        if (strlen($str) == 0 ) {
            return TRUE;
        }
        
        $regexp="/^(\d{4})-(\d{2})-(\d{2})$/"; 
        preg_match($regexp, $str, $matches);
        
        if (empty($matches)) {
            
            $this->ci->form_validation->set_message('external_callbacks', 'The %s field can not be date');
            return FALSE;
        }
        
        return checkdate($matches[2], $matches[3], $matches[1]);
    }
    
    /**
     * Hashes the password
     * 
     * @param string $password Password
     * @return mixed
     */
    public function hashPassword($password)
    {
        if (empty($password)) {
            return FALSE;
        }
        
        return password_hash($password, PASSWORD_BCRYPT);
    }
    
    /**
     * Verify password
     * 
     * @param int $userId
     * @param string $password
     * @return boolean
     */
    public function hashPasswordDb($userId, $password)
    {
        $userPassword = $this->auth_model->getPassword($userId);
        if (!$userPassword) {
            return FALSE;
        }
        
        if (password_verify($password, $userPassword->password)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    /**
     * Registered new user
     * 
     * @param string $userName
     * @param string $password
     * @param string $lastName
     * @param string $firstName
     * @param string $gender
     * @param string $dateOfBirth
     */
    public function register($userName, $password, $lastName, $firstName, $gender, $dateOfBirth)
    {
        $groups = array();
        if (isset($this->defaultGroup) && intval($this->defaultGroup) == TRUE) {
            $groups[] = $this->defaultGroup;
        }
        
        $password = $this->hashPassword($password);
        $gender = $this->checkGender($gender);
        
        $userId = $this->auth_model->register($userName, $password, $lastName, $firstName, $gender, $dateOfBirth);
        
        if ($userId && !empty($groups)) {
            foreach ($groups as $group) {
                $this->auth_model->addToGroup($userId, $group);
            }
        }
        
        if ($userId) {
            $this->setMessage('Register successful');
            return $userId;
        } else {
            $this->setError('Register unseccessful');
            return FALSE;
        }
    }
    
    public function edit($userId, $userName, $password, $lastName, $firstName, $gender, $dateOfBirth)
    {
        $password = $this->hashPassword($password);
        $gender = $this->checkGender($gender);
        
        $this->ci->auth_model->edit(
            $userId,
            $userName, 
            $password,
            $lastName,
            $firstName,
            $gender,
            $dateOfBirth
        );
        
        return TRUE;
    }
    
    /**
     * check gender
     * 
     * @param string $gender
     * @return mixed
     */
    public function checkGender($gender = '')
    {
        if (in_array($gender, $this->gender)) {
            return $gender;
        } else {
            return NULL;
        }
    }
    
    /**
     * Logout
     * 
     * @return boolean
     */
    public function logout()
    {
        $this->ci->session->unset_userdata( array('userid' => '', 'username' => '') );
        $this->ci->session->sess_destroy();
        $this->ci->session->sess_create();
        
        return TRUE;
    }
    
    /**
     * 
     * @return boolean
     */
    public function loggedIn()
    {
        return (bool) $this->ci->session->userdata('username');
    }
    
    /**
     * Login
     * 
     * @param string $userName
     * @param string $password
     * @return boolean
     */
    public function login($userName, $password)
    {
        if (empty($userName) || empty($password)) {
            $this->setError('username and password field is required');
            return FALSE;
        }
        
        $user = $this->auth_model->user(array(
            'username' => $password
        ));
        
        if ($user) {
            
            $passwordVerify = $this->hashPasswordDb($user->userid, $password);
            if ($passwordVerify === TRUE) {

                $this->createSession($user);
                $this->setMessage("Login successful");
                return TRUE;
            }
        }
        
        $this->setError('Login unseccessful. Login or Password incorrect.');
        return FALSE;
    }
    
    /**
     * set user session
     * 
     * @param object $user
     * @return boolean
     */
    public function createSession($user)
    {
        //$ipAddress = $this->ci->input->ip_address();
        $sessionData = array(
            'userid'    => $user->userid,
            'username'  => $user->username
        );
        
        $this->ci->session->set_userdata($sessionData);
        
        return TRUE;
    }

    /**
     * is admin
     * 
     * @param int $userId
     * @return boolean
     */
    public function isAdmin($userId = FALSE)
    {
        $adminGroupId = $this->ci->config->item('admin_group', 'ciauth');
        
        $userId || $userId = $this->getUserId();
        
        return $this->inGroup($adminGroupId, $userId);
    }
    
    public function getUserId()
    {
        return $this->ci->session->userdata('userid');
    }
    
    /**
     * check user in group
     * 
     * @param int $checkGroupId Group check
     * @param int $userId
     * 
     * @return boolean
     */
    public function inGroup($checkGroupId, $userId = FALSE)
    {
        if (empty($checkGroupId)) {
            return FALSE;
        }
        
        $userId || $userId = $this->ci->session->userdata('userid');
        
        $userGroups = $this->auth_model->getUserGroups($userId);
        
        $groupsArray = array();
        if ($userGroups) {
            foreach ($userGroups as $group) {
                $groupsArray[$group->groupid] = $group->name;
            }
        } else {
            return FALSE;
        }
        
        $groups = array_keys($groupsArray);
        if (in_array($checkGroupId, $groups)) {
            return TRUE;
        }
        
        return FALSE;
    }
    
    /**
     * display link sorting
     * 
     * @param string $field
     * @return string
     */
    public function displaySort($field)
    {
        // check allowed fields
        if (!in_array($field, $this->ci->auth_model->userSortFields)) {
            return '';
        }
        
        $inputField = $this->ci->input->get('sort');
        $inputMethod = $this->ci->input->get('mix');
        
        if ($inputField == $field) {
            // Alternative sort
            $isAlt = ( ($inputMethod != 'desc' && $inputField == $field) ? '-alt' : '');
            $method = ($inputField == $field && 'desc' == $inputMethod ? 'asc' : 'desc');
        } else {
            $isAlt = '-alt';
            $method = 'desc';
        }
        
        $link= "<a href=\"?" .
                 http_build_query(array(
                    'sort'=> $field,
                    'mix' => $method
                ), '', '&amp;')
            . "\"><i class=\"glyphicon glyphicon-sort-by-alphabet" . $isAlt . "\"></i></a>";
        return $link;
    }
    
    /**
     * set error message
     * 
     * @param string $error
     */
    public function setError($error)
    {
        $this->errors[] = $error;
    }
    
    /**
     * set message
     * 
     * @param string $msg
     */
    public function setMessage($msg)
    {
        $this->messages[] = $msg;
    }
    
    /**
     * get all errors message
     * 
     * @return string
     */
    public function errors()
    {
        $output = '';
        if (!empty($this->errors)) {
            foreach ($this->errors as $error) {
                $output .= $this->error_start_delimiter . $error . $this->error_end_delimiter;
            }
        }

        return $output;
    }
    
    /**
     * get messages
     * 
     * @return string
     */
    public function message()
    {
        $output = '';
        if (!empty($this->messages)) {
            foreach ($this->messages as $msg) {
                $output .= $this->message_start_delimiter . $msg . $this->message_end_delimiter;
            }
        }

        return $output;
    }
}