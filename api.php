<?php
 	require_once("Rest.inc.php");
	include_once("Connection.php");	
	class API extends REST {
		private $hookup;

		public function __construct(){
			parent::__construct();
			$this->hookup=Connection::doConnect();
		}

		public function processApi(){
			$func = strtolower(trim(str_replace("/","",$_REQUEST['x'])));
			if((int)method_exists($this,$func) > 0)
				$this->$func();
			else
				$this->response('', 404); 
		}

		private function login(){
			if($this->get_request_method() != "GET"){
				$this->response('', 406);
			}

			$cpf = mysql_real_escape_string($this->_request['cpf']);	
			$password = mysql_real_escape_string($this->_request['password']);
			if(!empty($cpf) and !empty($password)){
				$query = "SELECT IDCLIENTE, CPF, SENHA FROM cliente WHERE CPF = '$cpf' AND SENHA = '".md5($password)."' LIMIT 1";
				$r = $this->hookup->query($query) or die($this->hookup->error.__LINE__);

				if($r->num_rows > 0) {
					$result = $r->fetch_assoc();
					$success = array('status' => "success", "msg" => "login efetuado com sucesso.");	
					$this->response($this->json($success), 200);
				}else{
					$error = array('status' => "failed", "msg" => "login nao existe.");
					$this->response($this->json($error), 200);
				}
			}
		}
		
		private function Transacoes(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$query="SELECT distinct t2.CPF as DESTINATARIO, t3.CPF as CEDENTE, t4.DESCRICAO, t1.VALOR_TRANSACAO, t1.DATA_REGISTRO FROM transacao t1 INNER JOIN cliente t2 ON t1.cliente_IDCLIENTE = t2.IDCLIENTE INNER JOIN cliente t3 ON t1.cliente_IDCLIENTE_PAGADOR = t3.IDCLIENTE INNER JOIN tipo_transacao t4 ON t1.tipo_transacao_IDTIPO = t4.IDTIPO WHERE t1.cliente_IDCLIENTE = 1 OR t1.cliente_IDCLIENTE_PAGADOR = 1 ORDER BY t1.DATA_REGISTRO ASC";

			$r = $this->hookup->query($query) or die($this->hookup->error.__LINE__);

			if($r->num_rows > 0){
				$result = array();
				while($row = $r->fetch_assoc()){
					$result[] = $row;
				}
				$this->response($this->json($result), 200); 
			}
			$error = array('status' => "failed", "msg" => "nao ha registros para serem exibidos.");
			$this->response($this->json($error), 200);
		}

		private function SendToken(){
			if($this->get_request_method() != "GET"){
				$this->response('', 406);
			}
			
			$cpf = mysql_real_escape_string($this->_request['cpf']);		
			$welcome = mysql_real_escape_string($this->_request['welcome']);		
			$query = "SELECT IDCLIENTE FROM cliente WHERE CPF = $cpf LIMIT 1";
			$result = $this->hookup->query($query) or die($this->hookup->error.__LINE__);
			if($result){
			    while ($row = $result->fetch_object()){
			    	$value=get_object_vars($row);
			        $id =  $value['IDCLIENTE'];
			   	}
			}

			if(!empty($id)){
				$token = rand(10000, 99999);
				$query = "SELECT DDD, NUMERO FROM telefone WHERE cliente_IDCLIENTE = $id LIMIT 1";
				$result = $this->hookup->query($query) or die($this->hookup->error.__LINE__);
				if($result){
				    while ($row = $result->fetch_object()){
				    	$value=get_object_vars($row);
				        $telefoneCliente =  $value['DDD'] . $value['NUMERO'];
				    }
				}
				if($welcome=='1')
					$textoMensagem = "FaSimples: Seja bem-vindo a sua agencia digital, seu numero foi confirmado. A partir de agora toda transacao sera validada por um token enviado via SMS";
				else
					$textoMensagem = "Para confirmar a operacao no FaSimples digite o seguinte token de acesso: $token";
				$textoMensagem = urlencode($textoMensagem);
				$opts = array(
				  'http' => array('ignore_errors' => true)
				);
				//cria contexto de ignore error
				$context = stream_context_create($opts);

				$urlSms = "http://portal.assertivasolucoes.com.br/api/1.0.0/sms/envio/unitario?empresa=horizon-four&usuario=horizon-four&senha=Abraao@2016&numero=$telefoneCliente&mensagem=$textoMensagem&rota=1";

				$file = file_get_contents($urlSms);				
				$query = "INSERT INTO smstoken(cliente_IDCLIENTE, TOKEN, DATA_REGISTRO) VALUES ($id, '".md5($token)."', Current_TimeStamp)";
				$r = $this->hookup->query($query) or die($this->hookup->error.__LINE__);
				$success = array('status' => "success", "msg" => "token enviado.");
				$this->response($this->json($success),200);
			}else{
				$error = array('status' => "failed", "msg" => "token invalido.");
				$this->response($this->json($error), 200);
			}
		}

		private function TokenValidation(){	
			if($this->get_request_method() != "GET")
				$this->response('', 406);
			
			$cpf = mysql_real_escape_string($this->_request['cpf']);
			$token = mysql_real_escape_string($this->_request['token']);
			
			$query = "SELECT IDCLIENTE FROM cliente WHERE CPF = $cpf LIMIT 1";
			$result = $this->hookup->query($query) or die($this->hookup->error.__LINE__);
			if($result){
			    while ($row = $result->fetch_object()){
			    	$value=get_object_vars($row);
			        $id =  $value['IDCLIENTE'];
			   	}
			}

			if(isset($token) AND isset($id)){	
				$query="SELECT distinct IDSMS FROM smstoken WHERE cliente_IDCLIENTE = $id AND DATA_REGISTRO = (SELECT MAX(DATA_REGISTRO) FROM smstoken WHERE cliente_IDCLIENTE = $id) AND TOKEN = '".md5($token)."'";

				$r = $this->hookup->query($query) or die($this->hookup->error.__LINE__);
				if($r->num_rows > 0){
					$success = array('status' => "success", "msg" => "token valido.");
					$this->response($this->json($success), 200);
				}
				else{
					$error = array('status' => "failed", "msg" => "token invalido.");
					$this->response($this->json($error), 200);
				}
			}
		}
		
		private function Cadastro(){	
			if($this->get_request_method() != "GET")
				$this->response('', 406);
			
			$cpf = mysql_real_escape_string($this->_request['cpf']);
			$ddd = substr(mysql_real_escape_string($this->_request['telefone']), 0, 2);
			$numero = substr(mysql_real_escape_string($this->_request['telefone']), 2, 11);
			$senha = mysql_real_escape_string($this->_request['password']);
		
			//insere o cadastro
			$query = "INSERT INTO cliente (CPF, SALDO, SENHA) VALUES ($cpf, 5000.00, '".md5($senha)."')";
			$result = $this->hookup->query($query) or die($this->hookup->error.__LINE__);

			//realiza a consulta para pegar o codigo do cliente e vincular à base de telefones
			$query = "SELECT IDCLIENTE FROM cliente WHERE CPF = $cpf LIMIT 1";
			$result = $this->hookup->query($query) or die($this->hookup->error.__LINE__);
			if($result){
			    while ($row = $result->fetch_object()){
			    	$value=get_object_vars($row);
			        $id =  $value['IDCLIENTE'];
			   	}
			}
			//insere o telefone
			$query = "INSERT INTO telefone (cliente_IDCLIENTE, DDD, NUMERO) VALUES ($id, $ddd, $numero)";
			$result = $this->hookup->query($query) or die($this->hookup->error.__LINE__);

			$success = array('status' => "success", "msg" => "cadastro realizado com sucesso.");
			$this->response($this->json($success), 200);
		}

		private function Transact(){
			if($this->get_request_method() != "GET"){
				$this->response('', 406);
			}

			$tipoOperacao = mysql_real_escape_string($this->_request['operacao']);
			$cpfCedente = mysql_real_escape_string($this->_request['cpfCedente']);
			$cpfDestino = mysql_real_escape_string($this->_request['cpfDestino']);
			$valor = mysql_real_escape_string($this->_request['valor']);
			$token = mysql_real_escape_string($this->_request['token']);			

			//pega o cpf do cliente
			$query = "SELECT IDCLIENTE FROM cliente WHERE CPF = $cpfCedente LIMIT 1";
			$result = $this->hookup->query($query) or die($this->hookup->error.__LINE__);
			if($result){
			    while ($row = $result->fetch_object()){
			    	$value=get_object_vars($row);
			        $id =  $value['IDCLIENTE'];
			   	}
			}

			$valor = (float) $valor;

			$query="SELECT distinct IDSMS FROM smstoken WHERE cliente_IDCLIENTE = $id AND DATA_REGISTRO = (SELECT MAX(DATA_REGISTRO) FROM smstoken WHERE cliente_IDCLIENTE = $id) AND TOKEN = '".md5($token)."'";

			$r = $this->hookup->query($query) or die($this->hookup->error.__LINE__);
			if($r->num_rows <= 0){
				$error = array('status' => "failed", "msg" => "token invalido, abortar.");
				$this->response($this->json($error), 200);
			}

			if($tipoOperacao==2){//deposito
				$ssql = "UPDATE cliente SET SALDO = SALDO + ".$valor." WHERE CPF='".$cpfCedente."'";				
				$r = $this->hookup->query($query) or die($this->hookup->error.__LINE__);
			}

			//realiza query de debito de valor da conta do cliente
			$ssql = "UPDATE cliente SET SALDO = SALDO - ".$valor." WHERE CPF='".$cpfCedente."'";				
			$r = $this->hookup->query($query) or die($this->hookup->error.__LINE__);
			
			if($tipoOperacao==2){//deposito
				//realiza query de atualizacao de valor da conta do destinatario
				$ssql = "UPDATE cliente SET SALDO = SALDO + ".$valor." WHERE CPF='".$cpfDestino."'";				
				$r = $this->hookup->query($query) or die($this->hookup->error.__LINE__);
			}

			$query = "INSERT INTO transacao(cliente_IDCLIENTE, cliente_IDCLIENTE_PAGADOR, DATA_REGISTRO, estabelecimento_IDESTABELECIMENTO, tipo_transacao_IDTIPO, VALOR_TRANSACAO) VALUES ($cpfDestino, $cpfCedente, Current_TimeStamp, 1, $tipoOperacao, $valor)";
				
			$r = $this->hookup->query($query) or die($this->hookup->error.__LINE__);
			$success = array('status' => "success", "msg" => "operacao realizado com sucesso.");
			$this->response($this->json($success),200);
		}

		private function json($data){
			if(is_array($data)){
				return json_encode($data);
			}
		}
	}
	
	$api = new API;
	$api->processApi();
?>