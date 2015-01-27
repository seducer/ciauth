<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -  
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in 
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function index()
    {
        $this->load->library('ciauth_lib');
        if ($this->ciauth_lib->loggedIn()) {
            redirect('/admin', 'refresh');
        }
        
        $this->load->helper(array('form'));
        $this->load->library(array('form_validation'));
        //validate form input
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == true) {
            if ( $this->ciauth_lib->login($this->input->post('username'), $this->input->post('password')) ) {
                //if the login is successful
                redirect('/admin', 'refresh');
            } else {
                $this->session->set_flashdata('message', $this->ciauth_lib->errors());
                redirect('/', 'refresh');
            }
        }
        
        $this->load->view('ciauth/login', array(
            'message' => $this->session->flashdata('message')
        ));
    }
    
    public function logout()
    {
        $this->load->library('ciauth_lib');
        $this->ciauth_lib->logout();
        redirect('/', 'refresh');
    }
    
    public function register()
    {
        $this->load->helper(array('form'));
        $this->load->library(array('ciauth_lib', 'form_validation'));
        
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('inputLogin', 'Username', 'required|callback_external_callbacks[ciauth_lib,userNameCheck]');
        $this->form_validation->set_rules('inputPassword', 'Password', 'required');
        $this->form_validation->set_rules('inputLastName', 'LastName');
        $this->form_validation->set_rules('inputFirstName', 'FirstName');
        $this->form_validation->set_rules('inputGender', 'Gender');
        $this->form_validation->set_rules('inputDateofbirth', 'Dateofbirth', 'callback_external_callbacks[ciauth_lib,dateCheck]');
        
        
        if ($this->form_validation->run() == FALSE) {
            $data['bodyWrap'] = $this->load->view('ciauth/add_form', array(
                'gender' => $this->config->item('gender', 'ciauth'),
                'user' => FALSE
            ), TRUE);
        } else {
            $newUserId = $this->ciauth_lib->register(
                set_value('inputLogin'), 
                set_value('inputPassword'),
                set_value('inputLastName'),
                set_value('inputFirstName'),
                set_value('inputGender'),
                set_value('inputDateofbirth')
            );

            if (!$newUserId) {
                $msg = $this->ciauth_lib->errors();
            } else {
                //$msg = $this->ciauth_lib->messeges();
                redirect('admin', 'refresh');
            }
            
            $data['bodyWrap'] = $msg;
        }
        
        $data['pageHeader'] = 'Add User';
        $this->load->view('index', $data);
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
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */