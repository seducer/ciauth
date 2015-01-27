<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Name: CI auth model 
 * 
 * Author: 
 */

class Ciauth_model extends CI_Model {
    
    /**
     * User keys to Identify
     * 
     * @var array
     */
    protected $userIdentifyKeys = array(
        'userid',
        'username'
    );

    /**
     * Allowed field for sort
     * 
     * @var array 
     */
    public $userSortFields = array(
        'userid',
        'username',
        'firstname',
        'lastname',
        'dateofbirth'
    );
    
    public $sortMethod = array('desc', 'asc');

    public function __construct() 
    {
        parent::__construct();
        
        $this->load->database();
    }
    
    /**
     * users
     * 
     * @return mixed
     */
    public function users($start = NULL, $limit = NULL, $sortField = NULL, $sortMethod = NULL)
    {
        $this->db->select('userid,username,createdon,lastname,firstname,gender,dateofbirth')
                ->from('users');
        
        if ($sortField !== NULL && $sortMethod !== NULL) {
            $this->db->order_by($sortField, $sortMethod);
        }
        
        if ($start !== NULL && $limit !== NULL) {
            $this->db->limit($limit, $start);
        }
        $query = $this->db->get();
        
        return ($query->num_rows() > 0 ? $query->result() : FALSE);
    }
    
    /**
     * count users
     * 
     * @return int
     */
    public function countUsers()
    {
        return $this->db->count_all_results('users');
    }
    
    /**
     * get user
     * 
     * @param array $userIdentifies
     * @return mixed
     */
    public function user($userIdentifies = array())
    {
        if (empty($userIdentifies)) {
            return FALSE;
        }
        
        foreach (array_keys($userIdentifies) as $key) {
            if (!in_array($key, $this->userIdentifyKeys)) {
                return FALSE;
            }
        }
        
        $this->db->select('userid,username,createdon,lastname,firstname,gender,dateofbirth')
                ->from('users');
        
        foreach ($userIdentifies as $key => $value) {
            $this->db->where($key, $value);
        }
        
        $this->db->limit(1)->order_by('userid', 'desc');
        
        $query = $this->db->get();
        
        return ($query->num_rows() > 0 ? $query->row() : FALSE);
    }
    
    /**
     * Checks username
     * 
     * @param string $userName
     * @return boolean
     */
    public function userNameCheck($userName = '')
    {
        if (empty($userName)) {
            return FALSE;
        }
        
        return $this->db->where('username', $userName)
                ->count_all_results('users') > 0;
    }
    
    /**
     * remove user
     * 
     * @param int $userId
     */
    public function remove($userId = NULL)
    {
        $this->db->delete('users', array('userid' => $userId));
        return $this->db->affected_rows();
    }
    
    
    /**
     * register
     * 
     * @param string $userName
     * @param string $password
     * @param string $lastName
     * @param string $firstName
     * @param string $gender
     * @param string $dateOfBirth
     * 
     * @return int $id user id
     */
    public function register($userName, $password, $lastName, $firstName, $gender, $dateOfBirth)
    {
        // Data table
        $data = array(
            'username'    => $userName,
            'createdon'   => time(),
            'password'    => $password,
            'lastname'    => $lastName,
            'firstname'   => $firstName,
            'gender'      => $gender,
            'dateofbirth' => $dateOfBirth
        );
        
        $this->db->insert('users', $data);
        
        $id = $this->db->insert_id();
        
        return $id;
    }
    
    /**
     * edit user
     * 
     * @param int $userId
     * @param string $userName
     * @param string $password
     * @param string $lastName
     * @param string $firstName
     * @param string $gender
     * @param string $dateOfBirth
     * 
     */
    public function edit($userId, $userName, $password, $lastName, $firstName, $gender, $dateOfBirth)
    {
        $data = array(
            'username'    => $userName,
            'password'    => $password,
            'lastname'    => $lastName,
            'firstname'   => $firstName,
            'gender'      => $gender,
            'dateofbirth' => $dateOfBirth
        );
        
        $this->db->where('userid', $userId);
        $this->db->update('users', $data);
        
        return $this->db->affected_rows();
    }
    
    
    /**
     * Add User in group
     * 
     * @param int $userId
     * @param int $groupId
     */
    public function addToGroup($userId, $groupId)
    {
        $this->db->insert('users_group', array(
            'userid'  => $userId,
            'groupid' => $groupId
        ));
    }
    
    /**
     * user groups
     * 
     * @param int $id user id
     * @return mixed
     */
    public function getUserGroups($id = FALSE)
    {
        $this->db->select('userid,groupid,name')
                ->from('users_group')
                ->join('groups', 'groups.id=users_group.groupid', 'inner');
        
        if ($id) {
            $this->db->where('userid', $id);
        }
        
        $query = $this->db->get();
        
        return ($query->num_rows() > 0 ? $query->result() : FALSE);
    }
    
    
    /**
     * user hash password
     * 
     * @param int $userId
     * @return mixed
     */
    public function getPassword($userId)
    {
        $query = $this->db->select('password')
                ->where('userid', $userId)
                ->limit(1)
                ->order_by('userid', 'desc')
                ->get('users');
        return ($query->num_rows() > 0 ? $query->row() : FALSE);
    }
}