<?php

// Definindo o fuso horário
// Altere para o fuso horário da sua localização ou do seu PC
define('TIMEZONE', 'Africa/Addis_Ababa');
date_default_timezone_set(TIMEZONE);

/**
 * Função para retornar o tempo desde a última atividade
 * Exemplo de retorno: "Active" (ativo agora) ou "3 hours ago" (há 3 horas)
 * 
 * @param string $date_time Data/hora do último acesso no formato válido
 * @return string Texto indicando quanto tempo passou desde o último acesso
 */
function last_seen($date_time){

   $timestamp = strtotime($date_time);	
   
   // Unidades de tempo para exibir
   $strTime = array("segundo", "minuto", "hora", "dia", "mês", "ano");
   
   // Limites para conversão entre unidades
   $length = array("60","60","24","30","12","10");

   $currentTime = time();
   if($currentTime >= $timestamp) {
		$diff = $currentTime - $timestamp;
		
		// Converte o tempo para a maior unidade possível
		for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) {
			$diff = $diff / $length[$i];
		}

		$diff = round($diff);
		
		// Se foi menos de 59 segundos, retorna "Ativo"
		if ($diff < 59 && $strTime[$i] == "segundo") {
			return 'Ativo';
		}else {
			// Retorna tempo decorrido no formato: "X unidade(s) atrás"
			return $diff . " " . $strTime[$i] . "(s) atrás";
		}
		
   }
}
