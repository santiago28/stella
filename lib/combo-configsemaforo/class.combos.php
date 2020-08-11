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
		$consulta = parent::consulta("
		SELECT 
		id_mes,
		MONTHNAME(STR_TO_DATE(id_mes, '%m')) nombre_mes		
		FROM 
		semaforo 
		GROUP BY id_mes	
		ORDER BY id_mes
		");
		$num_total_registros = parent::num_rows($consulta);
		if($num_total_registros>0)
		{
			$menu1 = array();
			while($row = parent::fetch_assoc($consulta))
			{
				$code = $row["id_mes"];
				$name = $row["nombre_mes"];				
				$menu1[$code]=$name;
			}
			return $menu1;
		}
		else
		{
			return false;
		}
	}
	function lista2()
	{
		
		if($this->id_grupo==1 ||$this->id_grupo==4){
		$consulta = parent::consulta("
		SELECT 
		semaforo.id_tema,
		tema.nombre_tema
		FROM 
		semaforo,tema
		WHERE
		semaforo.id_tema=tema.id_tema and
		semaforo.id_mes='".$this->code."' 
		group by id_tema
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
	
	} else{
	
	$consulta = parent::consulta("
		SELECT 
		semaforo.id_tema,
		tema.nombre_tema
		FROM 
		semaforo,tema
		WHERE
		semaforo.id_tema=tema.id_tema and
		semaforo.id_componente='".$this->id_componente."' and 
		semaforo.id_mes='".$this->code."' 
		group by id_tema
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
	
	}
		
	function lista3()
	{
		$consulta = parent::consulta("
		SELECT 
		semaforo.id_contrato,
		prestador.nombre_prestador,
		modalidad.abr_modalidad
		FROM
		semaforo,prestador,modalidad
		WHERE
		semaforo.id_prestador=prestador.id_prestador and
		semaforo.id_modalidad=modalidad.id_modalidad and
		semaforo.id_mes='".$this->code."' and
		semaforo.id_tema='".$this->code2."' 
		group by id_contrato 
		order by nombre_prestador
		");
		$num_total_registros = parent::num_rows($consulta);
		if($num_total_registros>0)
		{
			$menu3 = array();
			while($row = parent::fetch_assoc($consulta))
			{
				$code3 = $row["id_contrato"];
				$name = $row["id_contrato"].":  ".$row["abr_modalidad"]." - ".$row["nombre_prestador"];				
				$menu3[$code3]=$name;
				
			}
			return $menu3;
		}
		else
		{
			return false;
		}
	}		
}
?>