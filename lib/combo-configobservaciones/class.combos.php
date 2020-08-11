<?php

class selects extends MySQL
{
	var $code = "";
	var $code2 = "";
	var $code3 = "";
	var $id_componente = "";
	var $id_grupo = "";
	
	
	
	function lista1()
	{
		if($this->id_grupo==1 ||$this->id_grupo==4){
		$consulta = parent::consulta("
		SELECT 
		id_componente,nombre_componente 
		FROM 
		componente 
		WHERE
		estado='1' 
		order by nombre_componente
		");
		$num_total_registros = parent::num_rows($consulta);
		if($num_total_registros>0)
		{
			$menu1 = array();
			while($row = parent::fetch_assoc($consulta))
			{
				$code = $row["id_componente"];
				$name = $row["nombre_componente"];				
				$menu1[$code]=$name;
			}
			return $menu1;
		}
		else
		{
			return false;
		}
		
	} else{
	$consulta = parent::consulta("
		SELECT 
		id_componente,nombre_componente 
		FROM 
		componente 
		WHERE
		id_componente='".$this->id_componente."' and 
		estado='1' 
		order by nombre_componente
		");
		$num_total_registros = parent::num_rows($consulta);
		if($num_total_registros>0)
		{
			$menu1 = array();
			while($row = parent::fetch_assoc($consulta))
			{
				$code = $row["id_componente"];
				$name = $row["nombre_componente"];				
				$menu1[$code]=$name;
			}
			return $menu1;
		}
		else
		{
			return false;
		}
	
	
	}
	
	}
	function lista2()
	{
		$consulta = parent::consulta("
		SELECT 
		id_tema,nombre_tema  
		FROM 
		tema
		WHERE
		id_componente='".$this->code."' and
		estado='1'
		order by nombre_tema
			
		");
		$num_total_registros = parent::num_rows($consulta);
		if($num_total_registros>0)
		{
			$menu2 = array();
			while($row = parent::fetch_assoc($consulta))
			{
				
				$code2 = $row["id_tema"];
				$name = $row["nombre_tema"];				
				$menu2[$code2]=$name;
			}
			return $menu2;
		}
		else
		{
			return false;
		}
	}
		
	function lista3()
	{
		/*
		$consulta = parent::consulta("
		SELECT 
		contrato_x_sede.id_sede,sede.nombre_sede  
		FROM contrato_x_sede,sede
		WHERE
		contrato_x_sede.id_sede=sede.id_sede and
		contrato_x_sede.id_prestador='".$this->code."' and
		contrato_x_sede.id_modalidad='".$this->code2."' and
		contrato_x_sede.estado='1'	
		group by id_sede 
		order by nombre_sede
		");
		$num_total_registros = parent::num_rows($consulta);
		if($num_total_registros>0)
		{
			$menu3 = array();
			while($row = parent::fetch_assoc($consulta))
			{
				$code3 = $row["id_sede"];
				$name = $row["nombre_sede"];				
				$menu3[$code3]=$name;
				
			}
			return $menu3;
		}
		else
		{
			return false;
		}
		*/	
	}	
}
?>