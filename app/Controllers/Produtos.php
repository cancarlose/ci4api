<?php

   namespace App\Controller;

   use CodeIgniter\RESTful\ResourceController;
   use Ramsey\Uuid\Uuid;

   class Produtos extends ResourceController {
      // Atributos
      private $produtoModel;
      private $token = '123456789abcdefghi';

      // Instanciamento
      public function __construct() 
      {
         $this->$produtoModel = new App\Models\ProdutosModel();
      }

      // Validação
      private function validaToken()
      {
         return $this->request->getHeaderLine('token') == $this->token;
      }

      // Serviço de retorno GET()
      public function listAll()
      {
         $produtos = $this->$produtoModel->findAll();

         return $this->response->setJSON($produtos);
      }

      // Retorno por ID GET(id)
      public function listById($id)
      {
         $produto = Produto::find($id);

         return $this->response->setJSON($produto);
      }

      // Serviço de criação POST()
      public function createProduct()
      {
         $response = [];
         // Validação do token
         if($this->validaToken() == true){
            // Dados da requisição para salvar
            $newProduto['nome'] = $this->request->getPost('nome');
            $newProduto['valor'] = $this->request->getPost('valor');

            try { 
               if($this->produtoModel->insert($newProduto)){
                  // deu certo
                  return $this->response->setStatusCode(201)->setJSON([
                     'response' => 'success',
                     'msg' => 'Produto criado com sucesso!'
                  ]);
               } else{
                  return $this->response->setStatusCode(404)->setJSON([
                     'response' => 'error',
                     'msg' => 'Erro ao salvar o produto!',
                     'errors' => $this->produtoModel->errors()
                  ]);
               }
            }catch (Exception $e) {
               return $this->response->setStatusCode(404)->setJSON([
                  'response' => 'error',
                  'msg' => 'Erro ao salvar o produto!',
                  'errors' => [
                     'exception' => $e->getMessage()
                  ]
               ]);
            }

         } else{
            return $this->response->setStatusCode(404)->setJSON([
               'response' => 'error',
               'msg' => 'Token Inválido!'
            ]);
         }

         return $this->response->setJSON();
      }

      // Serviço de atualização PUT()
      public function updateById($id) 
      {
         $produto = $this->produtoModel->find($id);

         $response = [];
         // Validação do token
         if($this->validaToken() == true){
            // Verifica se o produto foi encontrado
            if(!$produto) {
                  return $this->response->setStatusCode(404)->setJSON([
                     'response' => 'error',
                     'msg' => 'Produto não encontrado!'
                  ]);
            }

            // Dados da requisição para atualizar
            $newProduto['nome'] = $this->request->getPost('nome');
            $newProduto['valor'] = $this->request->getPost('valor');

            try { 
                  if($this->produtoModel->update($id, $newProduto)){
                     // deu certo
                     return $this->response->setStatusCode(200)->setJSON([
                        'response' => 'success',
                        'msg' => 'Produto atualizado com sucesso!'
                     ]);
                  } else{
                     return $this->response->setStatusCode(404)->setJSON([
                        'response' => 'error',
                        'msg' => 'Erro ao atualizar produto!',
                        'errors' => $this->produtoModel->errors()
                     ]);
                  }
            } catch (Exception $e) {
                  return $this->response->setStatusCode(404)->setJSON([
                     'response' => 'error',
                     'msg' => 'Erro ao atualizar o produto!',
                     'errors' => [
                        'exception' => $e->getMessage()
                     ]
                  ]);
            }

         } else{
            return $this->response->setStatusCode(404)->setJSON([
                  'response' => 'error',
                  'msg' => 'Token Inválido!'
            ]);
         }
      }
   }