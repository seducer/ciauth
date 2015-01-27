<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Web interface
 */

class Admin extends CI_Controller {

    /**
     * Users page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/admin
     *	- or -  
     * 		http://example.com/index.php/admin/index
     *
     */
    
    public function __construct() 
    {
        parent::__construct();
        
        // Loading auth library
        $this->load->library('ciauth_lib');
    }
    
    public function _remap($method, $params = array())
    {
        if (!$this->ciauth_lib->isAdmin()) {
            //redirect('/', 'location');
            show_error('Access denied');
        }
        
        $method = 'process_'.$method;
        
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        }
        show_404();
    }

    /**
     * users
     */
    public function process_index()
    {
        $this->load->library('pagination');

        $config['base_url'] = '/admin/index/page';
        $config['total_rows'] = $this->auth_model->countUsers();
        $config['per_page'] = 5; 
        $config['uri_segment'] = 4;
        $start = $this->uri->segment(4);

        $this->pagination->initialize($config); 
        
        $sortBy = NULL;
        $sortMethod = NULL;
        if ( in_array($this->input->get('sort'), $this->auth_model->userSortFields) ) {
            $sortBy = $this->input->get('sort', TRUE);
            $sortMethod = $this->input->get('mix', TRUE);
        }
        $data['users'] = $this->auth_model->users(($start ? $start : 0), $config['per_page'], $sortBy, $sortMethod);
        
        $usersView = $this->load->view('ciauth/users', $data, TRUE);
        $data['pageHeader'] = 'Users';
        $this->load->view('index', array(
            'bodyWrap' => $usersView
        ));
    }
    
    
    /**
     * external_callbacks method handles form validation callbacks that are not located
     * in the controller where the form validation was run.
     *
     * $param is a comma delimited string where the first value is the name of the model
     * where the callback lives. The second value is the method name, and any additional 
     * values are sent to the method as a one dimensional array.
     *
     * EXAMPLE RULE:
     *  callback_external_callbacks[some_model,some_method,some_string,another_string]
     */
    public function external_callbacks( $postdata, $param )
    {
        $param_values = explode( ',', $param ); 

        $library = $param_values[0];
        $this->load->library( $library );

        // Rename the second element in the array for easy usage
        $method = $param_values[1];

        // Check to see if there are any additional values to send as an array
        if( count( $param_values ) > 2 )
        {
            // Remove the first two elements in the param_values array
            array_shift( $param_values );
            array_shift( $param_values );

            $argument = $param_values;
        }

        // Do the actual validation in the external callback
        if( isset( $argument ) ) {
            $callback_result = $this->$library->$method( $postdata, $argument );
        } else {
            $callback_result = $this->$library->$method( $postdata );
        }

        return $callback_result;
    }

    
    public function process_edit($userId)
    {
        $this->load->helper(array('form'));
        $this->load->library(array('form_validation'));
        
        if (!is_numeric($userId) && $userId == 0 ) {
            show_error('!!!');
        }
        
        if ($userId) {
            $user = $this->auth_model->user(array('userid' => $userId));
        }
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('inputLogin', 'Username', 'required');
        $this->form_validation->set_rules('inputPassword', 'Password', 'required');
        $this->form_validation->set_rules('inputLastName', 'LastName');
        $this->form_validation->set_rules('inputFirstName', 'FirstName');
        $this->form_validation->set_rules('inputGender', 'Gender');
        $this->form_validation->set_rules('inputDateofbirth', 'Dateofbirth', 'callback_external_callbacks[ciauth_lib,dateCheck]');        
        
        if ($this->form_validation->run() == FALSE) {
            $data['bodyWrap'] = $this->load->view('ciauth/add_form', array(
                'gender' => $this->config->item('gender', 'ciauth'),
                'user'   => $user,
                'userid' => $userId
            ), TRUE);
        } else {
            
            $this->ciauth_lib->edit(
                $userId,
                set_value('inputLogin'), 
                set_value('inputPassword'),
                set_value('inputLastName'),
                set_value('inputFirstName'),
                set_value('inputGender'),
                set_value('inputDateofbirth')
            );
            
            redirect('admin', 'refresh');
        }
        
        $data['pageHeader'] = 'Add User';
        $this->load->view('index', $data);
    }
    
    /**
     * user info
     * @param integer $id
     */
    public function process_user($id)
    {
        $user = $this->auth_model->user(array(
            'userid' => (int) $id
        ));
        
        if (!$user) {
            show_404();
        }
        
        $data['bodyWrap'] = $this->load->view('ciauth/user', array('user' => $user), TRUE);
        $this->load->view('index', $data);
    }
    
    public function process_logout()
    {
        $this->ciauth_lib->logout();
        redirect('/', 'refresh');
    }
    
    public function process_removeuser($userId)
    {
        if ( empty($userId) ) {
            show_404();
        } 
        if ( !$this->ciauth_lib->isAdmin() ) {
            show_error('Permission denied');
        }
        
        $this->auth_model->remove($userId);
        
        redirect('admin', 'refresh');
    }
    
}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */