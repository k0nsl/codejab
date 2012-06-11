<?php
class Users extends RGNK_Controller {
	function Users() {
		parent::RGNK_Controller();
	}
	
	function index() {
		redirect('/');
	}
	
	function login() {
		$redir = null;
		
		$tmp = $this->session->flashdata('redir');
		
		if (!empty($tmp)) {
			$redir = $tmp;
		} else {
			$tmp = $this->input->get_post('redir');
			
			if (!empty($tmp)) {
				$redir = $tmp;
			}
		}
		
		if (is_user()) {
			redirect($redir);
		}
		
		$errors = array();
		$do = $this->input->get_post('do');
		$username = $this->input->get_post('username');
		$password = $this->input->get_post('password');
		
		if (!strcmp($do, 'login')) {
			if (empty($username)) {
				$errors[] = 'Empty Username';
			}
			
			if (empty($password)) {
				$errors[] = 'Empty Password';
			}
			
			if (empty($errors)) {
				if (($usr = $this->Users->authenticate($username, $password, true)) == FALSE) {
					$errors[] = 'Invalid Login';
				} else {
					if (is_null($redir)) {
						if ($usr['role'] == ADMIN_ROLE) {
							$redir = site_url() . 'admin/';
							
							if (true) {
							?>
							<script type="text/javascript">
							<!--
							location.href='<?= $redir ?>';
							//-->
							</script>
							<?
							exit;
							}
//							echo $redir;
//							exit;
						} else {
							$redir = site_url();
//							echo $redir;
//							exit;
						}
					}
					redirect($redir);
				}
			}
		}
		
		$data = array('errors' => $errors, 'redir' => $redir, 'username' => $username, 'password' => $password);
		$this->render_page('users/login', $data);
	}
	
	function logout() {
		$redir = 'login/';
		
		$tmp = $this->session->flashdata('redir');
		
		if (!empty($tmp)) {
			$redir = $tmp;
		}
		
		$this->session->unset_userdata('akey');
		redirect($redir);
	}
	
	function signup() {
		if (is_user()) {
			redirect('/');
		}

		
			$redir = site_url();
		
		$tmp = $this->session->flashdata('redir');
		
		if (!empty($tmp)) {
			$redir = $tmp;
		} else {
			$tmp = $this->input->get_post('redir');
			
			if (!empty($tmp)) {
				$redir = $tmp;
			}
		}
		
		if (is_user()) {
			redirect($redir);
		}
		
		$errors = array();
		$do = $this->input->get_post('do');
		$username = $this->input->get_post('username');
		$password = $this->input->get_post('password');
		$password2 = $this->input->get_post('password2');
		$email = $this->input->get_post('email');
		
		if (!strcmp($do, 'signup')) {
			if (empty($username)) {
				$errors[] = 'Empty Username';
			}
			
			if (empty($password)) {
				$errors[] = 'Empty Password';
			} else if (empty($password2)) {
				$errors[] = 'Unconfirmed Password';
			} else if (strcmp($password, $password2)) {
				$errors[] = 'Passwords dont match';
			}
			
			if (empty($email)) {
				$errors[] = 'Empty Email';
			}
			
			if (empty($errors)) {
				$data = array('username' => $username, 'password' => $password, 'email' => $email, 'role' => USER_ROLE, 'verified' => 0, 'submitter' => 0);
				
				if (($usr = $this->Users->add($data)) == FALSE) {
					$errors[] = 'Error creating account';
				} else {
//					$this->Users->authenticate($username, $password, true);
					$this->load->library('email');
				
					$cfg = array(
						'mailtype' => 'html'
					);
					
					$this->email->initialize($cfg);
					
					$this->email->from(FROM_EMAIL, FROM_NAME);
					$this->email->reply_to(FROM_EMAIL, FROM_NAME);
					$this->email->to($email);
					$this->email->subject(VERIFY_EMAIL_SUBJECT);
					
					$this->email->message($this->load->view('users/verify-email.php', array('verificationLink' => site_url() . 'users/verify/' . $usr . '/' . sha1($email)), true));
	
					$this->email->send();

					redirect('users/verify');
				}
			}
		}
		
		$data = array('errors' => $errors, 'redir' => $redir, 'username' => $username,
			'password' => $password, 'password2' => $password2, 'email' => $email);
		$this->render_page('users/signup', $data);
	}
	
	function verify($uid = null, $code = null) {
		if (!is_null($uid) && !is_null($code)) {
			$sql = sprintf("SELECT * FROM %s WHERE id=%d", USERS_DBTABLE, $uid);
			$res = $this->db->query($sql);
			
			$verified = false;
			$error = '';
			
			if ($res->num_rows()) {
				$usr = $res->row_array();
				
				if (!strcmp($code, sha1($usr['email']))) {
					$sql = sprintf("UPDATE %s SET verified=1 WHERE id=%d", USERS_DBTABLE, $uid);
					$this->db->query($sql);
					
					$verified = true;
				} else {
					$error = 'Invalid verification code';
				}
			} else {
				$error = 'User Not Found';
			}
			$res->free_result();

			if ($verified) {			
				$this->session->set_flashdata('g_Feedback', 'Your account has been verified');
			} else {
				$this->session->set_flashdata('b_Feedback', 'Unable to verify your account: ' . $error);
			}

			redirect('login/');
		}
		$data = array('code' => $code);
		$this->render_page('users/verify', $data);
	}
}
?>