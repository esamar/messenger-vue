<?php
abstract class Model
{
	protected $conn;
 
	public function __construct()
	{
		require_once ('app/config.php');
		$config = new Config();
		
		if(@$this->conn = mysqli_connect($config->host, $config->user,$config->pass)){

			mysqli_query ($this->conn,"SET NAMES 'utf8'");
			mysqli_select_db($this->conn,$config->db);
		}
		else
			die('No se conecto a la base de datos.');
	}

	public function consultaExt($query)
	{

		require_once ('app/config.php');

		$config = new Config();
echo $config->host_ext;
echo $config->user_ext;
echo $config->pass_ext;

		$serverName = "serverName\sqlexpress"; //serverName\instanceName

		$connectionInfo = array( 
								"Database"=>$config->host_ext, 
								"UID"=>$config->user_ext, 
								"PWD"=>$config->pass_ext
							);
		
		$conn = sqlsrv_connect( $serverName, $connectionInfo);

		if( $conn ) {
		     echo "Conexión establecida.<br />";
		}else{
		     echo "Conexión no se pudo establecer.<br />";
		     die( print_r( sqlsrv_errors(), true));
		}



		// echo $query;
		// $sql= mysqli_query($this->conn,$query);
		// $c=0;
		// $lista=array();			
		// while( $row = mysqli_fetch_assoc($sql)) {
		// 	$lista[$c] = $row;
		// 	$c++;
		// }
		// return $lista; //RETORNA ARRAY CON ROWS
	}

	public function consulta($query){
		// echo $query;
		$sql= mysqli_query($this->conn,$query);
		$c=0;
		$lista=array();			
		while( $row = mysqli_fetch_assoc($sql)) {
			$lista[$c] = $row;
			$c++;
		}
		return $lista; //RETORNA ARRAY CON ROWS
	}

	public function md5($txt){
		$config = new Config();
		return md5($config->md5.$txt.$config->md5);
	}

	public function sentencia($query){

		$result = mysqli_query($this->conn, $query);
		return $result; //RETORNA RESULTADO DE SENTENCIA
	}

	public function insert($query){

		$result = mysqli_query($this->conn, $query);
		return mysqli_insert_id ( $this->conn );
		// return $result; //RETORNA RESULTADO DE SENTENCIA
	}


}
?>
