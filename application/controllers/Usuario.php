<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario extends CI_Controller {

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
  public function visualizar_todos()
  {
  
    $alerta = null;

    $this->load->model('usuarios_model');

    $usuarios = $this->usuarios_model->get_usuarios();
    
    $dados = array(
      "alerta"  => $alerta,
      "usuarios"  => $usuarios
    );
    
    $this->load->view('usuario/visualizar_todos', $dados);

  }

  public function cadastrar()
  {
    $alerta = null;
    
    $dados = array(
      "alerta"  => $alerta
    );
    
    $this->load->view('usuario/visualizar_todos', $dados);
  }

  public function editar($id_usuario)
  {

    $alerta = null;
    $usuario = null;

    // Converte o id do usuário para int
    $id_usuario = (int) $id_usuario;

    if($id_usuario)
    {
      // Carrega o model
      $this->load->model('usuarios_model');

      // Verifica se o usuário está cadastrado no banco
      $existe = $this->usuarios_model->get_usuario($id_usuario);
      if($existe)
      {
        // Armazena em uma variável legível
        $usuario = $existe;

        if($this->input->post('editar') === "editar")
        {

          // Converte TAMBÉM o id do usuário, que vem do post, para int.
          $id_usuario_form = (int) $this->input->post('id_usuario');

          if($this->input->post('captcha')) redirect('conta/entrar');
          if($id_usuario !== $id_usuario_form) redirect('conta/entrar');

          // Definir regras de validação
          $this->form_validation->set_rules('email', 'EMAIL', 'required|valid_email');
          $this->form_validation->set_rules('senha', 'SENHA', 'required|min_length[3]|max_length[20]');
          $this->form_validation->set_rules('confirmar_senha', 'CONFIRMAR SENHA', 'required|min_length[3]|max_length[20]|matches[senha]');

          // Verificar se as regras são atendidas
          if($this->form_validation->run() === TRUE)
          {
            $usuario_atualizado = array(
              "email" => $this->input->post('email'),
              "senha" => $this->input->post('senha')
            );

            $atualizou = $this->usuarios_model->update_usuario($id_usuario, $usuario_atualizado);

            if($atualizou)
            {
              // Formulário inválido
              $alerta = array(
                "class"   => "success",
                "mensagem"  => "Atenção! O usuário foi atualizado com sucesso!"
              );
            }
            else
            {
              // Formulário inválido
              $alerta = array(
                "class"   => "danger",
                "mensagem"  => "Atenção! O usuário não foi atualizado. :("
              );
            }

          }
          else
          {
            // Formulário inválido
            $alerta = array(
              "class"   => "danger",
              "mensagem"  => "Atenção! O formulário não foi validado!<br>". validation_errors()
            );
          }

        }
      }
      else
      {
        // Define um valor vazio para o usuário
        $usuario = FALSE;

        // Usuário não existe
        $alerta = array(
          "class"   => "danger",
          "mensagem"  => "Atenção! O usuário não foi encontrado!"
        );
      }

    }
    else
    {
      // Usuário inválido
      $alerta = array(
        "class"   => "danger",
        "mensagem"  => "Atenção! O usuário informado está incorreto."
      );
    }

    $dados = array(
      "alerta"  => $alerta,
      "usuario" => $usuario
    );
    
    $this->load->view('usuario/editar', $dados);

  }

  public function deletar($id_usuario)
  {
    $alerta = null;

    // Converte o id do usuário para int
    $id_usuario = (int) $id_usuario;

    if($id_usuario)
    {
      // Carrega o model
      $this->load->model('usuarios_model');

      // Verifica se o usuário está cadastrado no banco
      $existe = $this->usuarios_model->get_usuario($id_usuario);
      if($existe)
      {
        $deletou = $this->usuarios_model->delete_usuario($id_usuario);

        if($deletou)
        {
          // Usuário deletado com sucesso
          $alerta = array(
            "class"   => "danger",
            "mensagem"  => "Atenção! O usuário foi excluído!"
          );
        }
        else
        {
          // Usuário não foi excluído
          $alerta = array(
            "class"   => "danger",
            "mensagem"  => "Atenção! O usuário não foi excluído!"
          );
        }

      }
      else
      {
        // Usuário não existe
        $alerta = array(
          "class"   => "danger",
          "mensagem"  => "Atenção! O usuário não foi encontrado!"
        );
      }
    }
    else
    {
      // Usuário inválido
      $alerta = array(
        "class"   => "danger",
        "mensagem"  => "Atenção! O usuário informado está incorreto."
      );
    }

    $dados = array(
      "alerta"  => $alerta
    );
    
    $this->load->view('usuario/deletar', $dados);
  }

}
