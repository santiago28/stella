<?php

class selects extends MySQL
{
	var $code = "";
	var $code1 = "";
	var $code2 = "";
	var $code3 = "";



	function lista1()
	{
		$consulta = parent::consulta("
		SELECT id_tema, nombre_tema
		FROM tema
		WHERE estado='1' AND id_componente = '".$this->code."'");
		$num_total_registros = parent::num_rows($consulta);
		$code = $this->code;
		if($num_total_registros>0)
		{
			$menu1 = array();
			while($row = parent::fetch_assoc($consulta))
			{
				$code1 = $row["id_tema"];
				$name = $row["nombre_tema"];
				$menu1[$code1]=$name;
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
		// $consulta = parent::consulta("
		// SELECT id_subtema, nombre_subtema
		// FROM subtema
		// WHERE id_componente ='".$this->code."' AND estado='1' AND id_tema = '".$this->code1."'");

			$consulta = parent::consulta("
			SELECT subtema.id_subtema as id_subtema, subtema.nombre_subtema as nombre_subtema
			FROM tema, subtema, componente, acta,pregunta_x_modalidad,modalidad
			WHERE tema.id_tema = subtema.id_tema AND
			subtema.id_componente = componente.id_componente AND
			subtema.id_subtema = pregunta_x_modalidad.id_subtema AND
			modalidad.id_modalidad = acta.id_modalidad AND
			componente.id_componente = '".$this->code."' AND tema.id_tema = '".$this->code1."' AND
			acta.id_acta = '".$this->code2."'
			GROUP BY subtema.id_subtema");

		$num_total_registros = parent::num_rows($consulta);
		if($num_total_registros>0)
		{
			$menu2 = array();
			while($row = parent::fetch_assoc($consulta))
			{

				$code2 = $row["id_subtema"];
				$name = $row["nombre_subtema"];
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
		SELECT id_pregunta, descripcion_pregunta
		FROM pregunta
		WHERE id_componente='".$this->code."' AND id_tema='".$this->code1."' AND id_subtema='".$this->code2."' AND estado='1' ORDER BY id_pregunta");
		$num_total_registros = parent::num_rows($consulta);
		if($num_total_registros>0)
		{
			$menu3 = array();
			while($row = parent::fetch_assoc($consulta))
			{
				$code3 = $row["id_pregunta"];
				$name = $row["descripcion_pregunta"];
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
