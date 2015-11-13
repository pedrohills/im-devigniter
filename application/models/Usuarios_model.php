<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios_model extends CI_Model {

  public function check_login($email, $senha)
  {
    // Definindo o parâmetro FROM
    $this->db->from('usuarios');
    
    // Definindo os parâmetros WHERE
    $this->db->where('email', $email);
    $this->db->where('senha', $senha);
    
    // Executando a QUERY no banco de dados
    $usuarios = $this->db->get();
    
    if($usuarios->num_rows())
    {
      $usuario = $usuarios->result_array();
      return $usuario[0];
    }
    else
    {
      return FALSE;
    }
  
  }

}
