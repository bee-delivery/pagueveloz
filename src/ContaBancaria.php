<?php

namespace BeeDelivery\PagueVeloz\src;



use BeeDelivery\PagueVeloz\Connection;

class ContaBancaria
{

    public $http;
    protected $cliente;

    public function __construct($clienteEmail = null, $clienteToken = null)
    {
        $this->http = new Connection($clienteEmail, $clienteToken);
    }

    /**
     * Listar contas bancárias cadastradas na PagueVeloz.
     *
     * @see https://www.pagueveloz.com.br/Help/Api/GET-api-v5-ContaBancaria
     * @return Array
     */
    public function listar()
    {
        return $this->http->get('api/v5/ContaBancaria');
    }

    /**
     * Cria uma nova conta bancária PagueVeloz.
     *
     * @see https://www.pagueveloz.com.br/Help/Api/POST-api-v5-ContaBancaria
     * @param Array conta
     * @return Array
     */
    public function criar($conta)
    {
        $conta = $this->setConta($conta);
        return $this->http->post('api/v5/ContaBancaria', ['form_params' => $conta]);
    }

    /**
     * Pesquisa um cliente PagueVeloz.
     *
     * @see https://www.pagueveloz.com.br/Help/Api/GET-api-v5-ContaBancaria-id
     * @param Array id
     * @return Array
     */
    public function encontrar($id)
    {
        return $this->http->get('api/v5/ContaBancaria/' . $id );
    }

    /**
     * Altera uma conta bancária PagueVeloz.
     *
     * @see https://www.pagueveloz.com.br/Help/Api/PUT-api-v5-ContaBancaria-id
     * @param Integer id
     * @param Array conta
     * @return Array
     */
    public function alterar($id, $conta)
    {
        $conta = $this->setConta($conta);
        return $this->http->put('api/v5/ContaBancaria/' . $id , ['form_params' => $conta]);
    }

    /**
     * Faz merge nas informações da conta.
     *
     * @param Array $conta
     * @return Array
     */
    public function setConta($conta)
    {
        try {
            if ( ! $this->conta_is_valid($conta) ) {
                throw new \Exception('Dados inválidos.');
            }

            $this->cliente = array(
                'CodigoBanco'               => '',
                'Operacao'                  => '',
                'CodigoAgencia'             => '',
                'DigitoAgencia'             => '',
                'NumeroConta'               => '',
                'DigitoConta'               => '',
                'Descricao'                 => '',
                'TipoConta'                 => '',
                'TipoTitular'               => '',
                'Titular'                   =>
                    [
                        'Nome'                  => '',
                        'Documento'             => '',
                        'TipoPessoa'            => 'NaoDefinido'
                    ],
                'DataValidadeSolicitada'    => ''
            );

            $this->conta = array_merge($this->cliente, $conta);
            return $this->conta;

        } catch (\Exception $e) {
            return 'Erro ao definir a conta. - ' . $e->getMessage();
        }
    }

    /**
     * Verifica se os dados da transferência são válidas.
     *
     * @param array $cliente
     * @return Boolean
     */
    public function conta_is_valid($cliente)
    {
        return ! (
            empty($cliente['CodigoBanco']) OR
            empty($cliente['CodigoAgencia']) OR
            empty($cliente['NumeroConta']) OR
            empty($cliente['DigitoConta']) OR
            empty($cliente['Descricao']) OR
            empty($cliente['TipoConta']) OR
            empty($cliente['TipoTitular']) OR
            empty($cliente['Titular']['Nome']) OR
            empty($cliente['Titular']['Documento'])
        );
    }
}

