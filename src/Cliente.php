<?php

namespace BeeDelivery\PagueVeloz\src;



use BeeDelivery\PagueVeloz\Connection;

class Cliente
{

    public $http;
    protected $cliente;

    public function __construct()
    {
        $this->http = new Connection();
    }

    /**
     * Efetua assinatura de um novo cliente PagueVeloz.
     *
     * @see https://www.pagueveloz.com.br/Help/Api/POST-api-v5-Assinar
     * @param Array cliente
     * @return Array
     */
    public function create($cliente)
    {
        $cliente = $this->setCliente($cliente);
        return $this->http->post('api/v5/Assinar', ['form_params' => $cliente]);
    }


    /**
     * Faz merge nas informações do cliente.
     *
     * @param Array $cliente
     * @return Array
     */
    public function setCliente($cliente)
    {
        try {

            if ( ! $this->cliente_is_valid($cliente) ) {
                throw new \Exception('Dados inválidos.');
            }

            $this->cliente = array(
                'Nome'                  => '',
                'Documento'             => '',
                'TipoPessoa'            => '',
                'Email'                 => '',
                'Endereco'              => '',
                'Telefones'             => '',
                'Usuario'               => '',
                'DataNascimento'        => '',
                'UrlNotificacao'        => '',
                'InscricaoEstadual'     => '',
                'InscricaoMunicipal'    => '',
                'Cupom'                 => ''
            );

            $this->cliente = array_merge($this->cliente, $cliente);
            return $this->cliente;

        } catch (\Exception $e) {
            return 'Erro ao definir o cliente. - ' . $e->getMessage();
        }
    }

    /**
     * Verifica se os dados da transferência são válidas.
     *
     * @param array $cliente
     * @return Boolean
     */
    public function cliente_is_valid($cliente)
    {
        return ! (
            empty($cliente['Nome']) OR
            empty($cliente['Documento']) OR
            empty($cliente['TipoPessoa']) OR
            empty($cliente['Email']) OR
            empty($cliente['Endereco']) OR
            empty($cliente['Telefones']) OR
            empty($cliente['Usuario'])
        );
    }
}

