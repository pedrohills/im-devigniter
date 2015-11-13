<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

  public function __construct()
  {      
    parent::__construct();

    // Verifica se o usuário NÃO está logado
    // e redireciona para a autenticação.
    if(!$this->session->userdata('logado'))
    {      
      redirect('conta/entrar');
    }
      
  }

  // Exibir informações sobre o nosso sistema.
  public function index(){
  
    $alerta = null;
    
    $dados = array(
      "alerta"  => $alerta
    );
    
    $this->load->view('dashboard/index', $dados);

  }

}
