<?php

/****
*This example file comes from simplelogin, originally created by Anthony Graddy
* http://codeigniter.com/wiki/SimpleLoginsecure
* modified by Hitesh Ubharani hiteshubharani@gmail.com
****/

class Example extends  CI_Controller {

	function Example()
	{
		parent::__construct();
		$this->load->library('simpleloginsecure');	
	}
	
	function index()
	{
		//This assumes you used the sample MySQL table
		$user_table = 'users';
		
		//Load the URL helper
		$this->load->helper('url');
		$this->load->library('session');
		
		
		/*
		//Uncomment this if you are using OBSession
		if($this->session->flashdata('error')) {	
			echo '<div class="error" style="background-color:red;">';
				echo $this->session->flashdata('error_text');
			echo '</div>';
		} elseif($this->session->flashdata('success')) {
			echo '<div class="success" style="background-color:green;">';
				echo $this->session->flashdata('success_text');
			echo '</div>';		
		}
		*/


		//BOF Status Info
		echo '<div id="status">';
			echo '<h3>User Status</h3>';
			if($this->session->userdata('logged_in')) {
				echo 'User logged in as ' . $this->session->userdata('username');
			} else {
				echo 'User not logged in';
			}
		echo '</div>';
		echo '<hr />';
		//EOF Status Info

		//BOF Create user
		echo '<div id="create">';
			echo '<h3>Create A User</h3>';
			echo '<form action="' . site_url('/example/create/') . '" method="post">';
				
				echo '<label for="create_username">Username:</label>';
				echo '<input type="text" id="create_username" name="create_username" value="" /><br />';

				echo '<label for="create_password">Password:</label>';
				echo '<input type="password" id="create_password" name="create_password" value="" /><br />';

				echo '<input type="submit" id="create" name="create" value="Create" />';

			echo '</form>';
		echo '</div>';
		echo '<hr />';
		//EOF Create user

		
		//BOF Login user
		if(!$this->session->userdata('logged_in')) {
			echo '<div id="login">';
				echo '<h3>Login</h3>';
				echo '<form action="' . site_url('/example/login/') . '" method="post">';
					
					echo '<label for="login_username">Username:</label>';
					echo '<input type="text" id="login_username" name="login_username" value="" /><br />';
	
					echo '<label for="login_password">Password:</label>';
					echo '<input type="password" id="login_password" name="login_password" value="" /><br />';
	
					echo '<input type="submit" id="login" name="login" value="Login" />';
	
				echo '</form>';
			echo '</div>';
			echo '<hr />';
		} else {
			echo '<div id="logut">';
				echo '<h3>Logut</h3>';
				echo '<a href="' . site_url('/example/logout/') . '">Click here to logout.</a>';
			echo '</div>';
			echo '<hr />';
			
		}
		//EOF Login user
		
		//BOF User table
		if($this->session->userdata('logged_in')) {
			//Grab user data from database
			$this->db->select('user_id, user_email');
			$query = $this->db->get($user_table);
			$user_array = $query->result_array();
			
			if(count($user_array) > 0) {
				echo '<div id="user_table">';
					echo '<h3>User Table</h3>';
					echo '<table>';
						echo '<tr>';
							echo '<th>';
								echo 'ID';
							echo '</th>';
							echo '<th>';
								echo 'Username';
							echo '</th>';
							echo '<th>';
								echo 'Delete';
							echo '</th>';
						echo '</tr>';
						foreach($user_array as $ua) {
							echo '<tr>';
								echo '<td>';
									echo $ua['user_id'];
								echo '</td>';
								echo '<td>';
									echo $ua['user_email'];
								echo '</td>';
								echo '<td>';
								
									echo '<a href="' . site_url('/example/delete/' . $ua['user_id']) . '" onclick="return confirm(\'Are you sure you want to delete '  . $ua['user_email'] . '?\')">Delete</a>';
								echo '</td>';
							echo '</tr>';
						}
					echo '</table>';
				echo '</div>';
				echo '<hr />';
			}
		}
		//EOF User table
		
	}
	
	function create()
	{
		//Load
		$this->load->helper('url');
		$this->load->library('form_validation');
		
		
		//Check incoming variables
		$rules['create_username']	= "required|min_length[4]|max_length[32]|alpha_dash";
		$rules['create_password']	= "required|min_length[4]|max_length[32]|alpha_dash";		

		$this->form_validation->set_rules($rules);

		/*$fields['create_username'] = 'Username';
		$fields['create_password'] = 'Password';
		
		$this->form_validation->set_fields($fields);*/
				
		if ($this->form_validation->run() == false) {
			/*
			//If you are using OBSession you can uncomment these lines
			$flashdata = array('error' => true, 'error_text' => $this->validation->error_string);
			$this->session->set_flashdata($flashdata); 
			$this->session->set_flashdata($_POST);
			*/
			redirect('/example/');			
		} else {
			//Create account
			if($this->simpleloginsecure->create($this->input->post('create_username'), $this->input->post('create_password'))) {
				/*
				//If you are using OBSession you can uncomment these lines
				$flashdata = array('success' => true, 'success_text' => 'Account Creation Successful!');
				$this->session->set_flashdata($flashdata);
				*/
				redirect('/example/');	
			} else {
				/*
				//If you are using OBSession you can uncomment these lines
				$flashdata = array('error' => true, 'error_text' => 'There was a problem creating the account.');
				$this->session->set_flashdata($flashdata); 
				$this->session->set_flashdata($_POST);
				*/
				redirect('/example/');			
			}			
		}
	}

	function delete($user_id)
	{
		/* This method can delete your current user account
		 * and you will still be logged in until you click
		 * the logout button (then you won't be able to login again')
		 */
		
		//Load
		$this->load->helper('url');
		
		echo "in delete, userid: " . $user_id;
		if($this->simpleloginsecure->delete($user_id)) {
			/*
			//If you are using OBSession you can uncomment these lines
			$flashdata = array('success' => true, 'success_text' => 'Deletion Successful!');
			$this->session->set_flashdata($flashdata);
			*/
			redirect('/example/');	
		} else {
			echo "in else of dete in example.php";
			/*
			//If you are using OBSession you can uncomment these lines
			$flashdata = array('error' => true, 'error_text' => 'There was a problem creating the account.');
			$this->session->set_flashdata($flashdata); 
			$this->session->set_flashdata($_POST);
			*/
			redirect('/example/');			
		}			
		
	}

	function login()
	{
		//Load
		$this->load->helper('url');
		$this->load->library('form_validation');
		
		//Check incoming variables
		$rules['login_username']	= "required|min_length[4]|max_length[32]|alpha_dash";
		$rules['login_password']	= "required|min_length[4]|max_length[32]|alpha_dash";		

		$this->form_validation->set_rules($rules);

		/*$fields['login_username'] = 'Username';
		$fields['login_password'] = 'Password';
		
		$this->form_validation->set_fields($fields);*/
				
		if ($this->form_validation->run() == false) {
			/*
			//If you are using OBSession you can uncomment these lines
			$flashdata = array('error' => true, 'error_text' => $this->validation->error_string);
			$this->session->set_flashdata($flashdata); 
			$this->session->set_flashdata($_POST);
			*/
			redirect('/example/');			
		} else {
			//Create account
			if($this->simpleloginsecure->login($this->input->post('login_username'), $this->input->post('login_password'))) {
				/*
				//If you are using OBSession you can uncomment these lines
				$flashdata = array('success' => true, 'success_text' => 'Login Successful!');
				$this->session->set_flashdata($flashdata);
				*/
				redirect('/example/');	
			} else {
				/*
				//If you are using OBSession you can uncomment these lines
				$flashdata = array('error' => true, 'error_text' => 'There was a problem logging into the account.');
				$this->session->set_flashdata($flashdata); 
				$this->session->set_flashdata($_POST);
				*/
				redirect('/example/');			
			}			
		}
	}

	function logout()
	{
		//Load
		$this->load->helper('url');
		//$this->load->library('class name');
		

		//Logout
		$this->simpleloginsecure->logout();
		redirect('/example/');
	}


}
?>