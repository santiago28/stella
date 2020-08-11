<?php

class selects extends MySQL
{
	var $code = "";
	var $code2 = "";
	var $code3 = "";



	function lista1()
	{
		$consulta = parent::consulta("
		SELECT
		id_prestador,nombre_prestador
		FROM
		prestador
		WHERE
		estado='1'
		order by nombre_prestador
		");
		$num_total_registros = parent::num_rows($consulta);
		if($num_total_registros>0)
		{
			$menu1 = array();
			while($row = parent::fetch_assoc($consulta))
			{
				$code = $row["id_prestador"];
				$name = $row["nombre_prestador"];
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
		$consulta = parent::consulta("
		SELECT
		contrato_x_sede.id_modalidad,modalidad.nombre_modalidad
		FROM
		contrato_x_sede,modalidad
		WHERE
		contrato_x_sede.id_modalidad=modalidad.id_modalidad and
		id_prestador='".$this->code."' and
		contrato_x_sede.estado='1'
		group by id_modalidad
		order by nombre_modalidad

		");
		$num_total_registros = parent::num_rows($consulta);
		if($num_total_registros>0)
		{
			$menu2 = array();
			while($row = parent::fetch_assoc($consulta))
			{

				$code2 = $row["id_modalidad"];
				$name = $row["nombre_modalidad"];
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
	}

	function lista4()
	{
		$consulta = parent::consulta("
		SELECT
		contrato_x_sede.id_sede,sede.nombre_sede, contrato_x_sede.id_contrato
		FROM contrato_x_sede,sede
		WHERE
		contrato_x_sede.id_sede=sede.id_sede and
		contrato_x_sede.id_prestador='".$this->code."' and
		contrato_x_sede.id_modalidad='".$this->code2."' and
		contrato_x_sede.estado='1'
		group by id_contrato
		order by nombre_sede
		");
		$num_total_registros = parent::num_rows($consulta);
		if($num_total_registros>0)
		{
			$menu3 = array();
			while($row = parent::fetch_assoc($consulta))
			{
				$code3 = $row["id_contrato"];
				$name = $row["id_contrato"];
				$menu3[$code3]=$name;

			}
			return $menu3;
		}
		else
		{
			return false;
		}
	}

	function lista5()
	{
		$consulta = parent::consulta("
		SELECT
		detalle_tipo_descuento.id, tipo_descuento.tipo_descuento as nombre, detalle_tipo_descuento.id_modalidad, detalle_tipo_descuento.descuento
		FROM detalle_tipo_descuento, tipo_descuento
		WHERE
		tipo_descuento.id = detalle_tipo_descuento.tipo_descuento and
		id_modalidad='".$this->code."'");
		$num_total_registros = parent::num_rows($consulta);
		if($num_total_registros>0)
		{
			$menu5 = array();
			while($row = parent::fetch_assoc($consulta))
			{
				$code3 = $row["id"];
				$name = $row["nombre"];
				$menu5[$code3]=$name;

			}
			return $menu5;
		}
		else
		{
			return false;
		}
	}

	function lista6()
	{
		$consulta = parent::consulta("
		SELECT
		sede.id_sede, sede.nombre_sede
		FROM contrato_x_sede, sede
		WHERE contrato_x_sede.id_sede = sede.id_sede and
		contrato_x_sede.id_contrato = '".$this->code."'");
		$num_total_registros = parent::num_rows($consulta);
		if($num_total_registros>0)
		{
			$menu6 = array();
			while($row = parent::fetch_assoc($consulta))
			{
				$code3 = $row["id_sede"];
				$name = $row["nombre_sede"];
				$menu6[$code3]=$name;

			}
			return $menu6;
		}
		else
		{
			return false;
		}
	}
}
?>
