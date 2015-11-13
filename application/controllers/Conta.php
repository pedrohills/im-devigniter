<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Conta extends CI_Controller {

  public function __construct()
  {      
    parent::__construct();

    if($this->session->userdata('logado'))
    {
      if(!$this->uri->segment(2) == "sair")
      {
        redirect('dashboard');
      }
    }
      
  }

  public function entrar(){
  
    $alerta = null;
  
    if($this->input->post('entrar') === "entrar")
    {
      if($this->input->post('captcha')) redirect('conta/entrar');
      
      // Define as regras de validação
      $this->form_validation->set_rules('email', 'EMAIL', 'required|valid_email');
      $this->form_validation->set_rules('senha', 'SENHA', 'required|min_length[6]|max_length[20]');
      
      // Executa as regras de validação
      if($this->form_validation->run() === TRUE)
      {
      
        // Carrega o model Usuarios_model
        $this->load->model('usuarios_model');
        
        // Armazenando os dados do formulário em variáveis
        $email = $this->input->post('email');
        $senha = $this->input->post('senha');
        
        // Executando o método check_login do model
        $login_existe = $this->usuarios_model->check_login($email, $senha);
        
        // Verificando se o os dados digitados estão corretos
        if($login_existe)
        {
          // Login autorizado... Iniciar sessão.
          $usuario = $login_existe;
          
          // Configura os dados da sessão
          $session = array(
            'email'       => $usuario["email"],
            'created'     => $usuario["created"],
            'logado'      => TRUE
          );

          // Inicializa a sessão
          $this->session->set_userdata($session);
          
          redirect('dashboard');
          
        }
        else
        {
          // Login inválido
          $alerta = array(
            "class"   => "danger",
            "mensagem"  => "Atenção! Login inválido, senha ou email incorretos."
          );
        }
        
      }
      else
      {
        $alerta = array(
          "class"   => "danger",
          "mensagem"  => "Atenção! Falha na validação do formulário!<br>". validation_errors()
        );
      }
    
    }
    
    $dados = array(
      "alerta"  => $alerta
    );
    
    $this->load->view('conta/entrar', $dados);
  }
  
  
  public function sair(){
    $this->session->sess_destroy();
    
    redirect('welcome');
  }
  

}
