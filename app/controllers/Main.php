<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MainController
 *
 * @author rodolfoneto
 */
class MainController extends ApplicationController
{
	private $conn;
	
	
	/**
	 * 
	 * @throws Exception
	 */
	public function initialize() {
	    parent::initialize();
	    set_include_path ( RA_LIBRARIES_PATH . "/jpgraph/" );
		
		$host = "slack.ckjstzy6gz0p.us-west-2.rds.amazonaws.com";
		$password = "wnJug9??vQbkJsKKT";
		$user = "bot";
		$dbname = "slack";

		$this->conn = pg_connect(sprintf('host=%s port=5432 user=%s password=%s dbname=%s',
									$host, $user, $password, $dbname));
		if ($this->conn === false) {
			throw new Exception(pg_last_error());
		}
	}

	public function index() {
	    
	}
	
	
	private function getData($userReq, $channel, $team) {
	    $userEscaped = pg_escape_string($userReq);
	    $channelEscaped = pg_escape_string($channel);
	    $teamEscaped = pg_escape_string($team);
		
	    $sql = $this->get_sql($channelEscaped, $teamEscaped, $userEscaped);

	    $result = pg_query($this->conn, $sql);
	    $respost = pg_fetch_all($result);

	    $values = array();
	    $days = array();
	    $flag = false;
	    for($i = 0; $i <= 7; $i++)
	    {
		for($e = 0; $e < count($respost); $e++)
		{
		    if($i == $respost[$e]['DiaSemana'] && !$flag)
		    {
			$values[$i] = $respost[$e]['valor'];
			$days[$i] = $respost[$e]['DiaSemana'];
			$flag = true;
		    }
		}
		if(!$flag)
		{

		    $values[$i] = '0.0000';
		    $days[$i] = $i;
		}
		$flag = false;
	    }
	    
	    array_shift($values);
	    array_shift($days);
	    
	    	    
	    $sql2 = $this->get_sql($channel, $team);
	    
	    $result2 = pg_query($this->conn, $sql2);
	    $respost2 = pg_fetch_all($result2);

	    $ds = $respost2[0]['DiaSemana'];
	    $qids = 0;
	    $arr = array();
	    $tds = 0;
	    for($i = 0; $i <= count($respost2); $i++)
	    {
		$item = $respost2[$i];
		if($item['DiaSemana'] != $ds)
		{
		    $arr[$ds] = $tds / $qids;
		    $ds = $respost2[$i]['DiaSemana'];
		    $qids = 0;
		    $tds = 0;
		}
		$tds += $item['valor'];
		$qids++;
	    }
	    
	    $arr2 = array();
	    for($i = 0; $i  <= 7; $i++)
	    {
		if(!isset($arr[$i]))
		{
		    $arr2[] = '0.0000';
		}
		else
		{
		    $arr2[] = $arr[$i];
		}
	    }
	    array_shift($arr2);

	    return  array('valores' => $values, 'valores_channel' =>$arr2, 'dias' => $days, 'user' => $respost[0]['User'], 'channel' => $respost[0]['Channel'], 'team' => $respost[0]['Team']);
	}



	public function rec() {
	    header('Content-Type: application/json');

		if($_REQUEST['channel_name'] == "directmessage")
		{
			echo "We don't analyze direct messages, only open channels!";
			return;
		} else if(pg_num_rows($this->get_count($_REQUEST['channel_id'], $_REQUEST['team_id'])) == 0) {
			$reply = (array(
				"channel" => $_REQUEST['channel_id'],
				"attachments" => array(array(
					"color" => "#6dcadb",
					"author_name" => $_REQUEST['user_name'],
					"title" => "Slackmetrics - Beta",
					"title_link" => "http://www.slackmetrics.us/signup/",
					"image_url" => urlencode("http://www.slackmetrics.us/img/howToGenerateToken.gif"),
					"fields" => array(
						"title" => "",
						"value" => "High",
						"short" => false
					)
				)),
					"text" => urlencode("We don't have any information to show. Did you register your token?"),
			));
		}
		else
		{
			$urlImage = 'http://rodolfoneto.com.br/slack/graph/' . "?u=" . $_REQUEST['user_id'] . '&c=' . $_REQUEST['channel_id'] . '&t=' . $_REQUEST['team_id'] . '&r=' . rand(1, 9999);
			$reply = (array(
				"channel" => $_REQUEST['channel_id'],
				"attachments" => array(array(
					"color" => "#6dcadb",
					"author_name" => $_REQUEST['user_name'],
					"title" => "Slackmetrics - Beta",
					"title_link" => "http://beta.slackmetrics.us:8080",
					"image_url" => urlencode($urlImage),
					"fields" => array(
						"title" => "",
						"value" => "High",
						"short" => false
					)
				)),
					"text" => urlencode("Here is your summary of last seven days"),
			));
		}
	    
	    if($_REQUEST['token'] == 'Kw2Z4S9ZGZNnp6py3KSnvUca') {
			//labtransp
			$url = 'https://hooks.slack.com/services/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';
	    } else if($_REQUEST['token'] == 'ZMTXvi7LpIJkQ7aOrxPZ8Flm') {
			//ESCOREBU de TESTE
			$url = "https://hooks.slack.com/services/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";
	    } else if($_REQUEST['token'] == 'YY8jEWSKTt1y0tjY6GLTKesT') {
			//CUMBUCA de TESTE
			$url = "https://hooks.slack.com/services/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";
	    } else if($_REQUEST['token'] == 'ErHFjUBgo3xSIegU58C9EBqF') {
			//
			$url = "https://hooks.slack.com/services/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";
	    } else {
			return;
		}

	    $result = 'payload=' . (json_encode($reply));

	    $ch = curl_init();
	    curl_setopt($ch,CURLOPT_URL, $url);
	    curl_setopt($ch,CURLOPT_POST, 1);
	    curl_setopt($ch,CURLOPT_POSTFIELDS, $result);
	    ob_start();
	    curl_exec($ch);
	    curl_close($ch);
	    ob_get_clean();

	    die;
	}



	public function graph()
	{

	    $rand = rand(2, 14);
	    
	    $dados = $this->getData($_REQUEST['u'], $_REQUEST['c'], $_REQUEST['t']);

	    $ydata = $dados['valores_channel'];
	    $ydata2 = $dados['valores'];

	    require_once ('jpgraph.php');
	    require_once ('jpgraph_line.php');

	    $graph = new Graph(399,299);
	    
	    $graph->SetScale("intlin", -1, 1);
	    $graph->xaxis->SetTickLabels($this->day_week());
	    
	    $graph->title->Set('Team: #' . $dados['team']);
	    $graph->subtitle->Set('Channel: ' . $dados['channel']);
	    
	    $graph->SetMargin(40,20,20,40);

	    $graph->xaxis->SetPos('min'); 
	    
	    $lineplot = new LinePlot($ydata);
	    $lineplot2 = new LinePlot($ydata2);
	    
	    $lineplot->SetLegend('#' . $dados['channel']);
	    $lineplot2->SetLegend($dados['user']);
	    
	    $graph->Add($lineplot);
	    $graph->Add($lineplot2);
	    $graph->Stroke();
	}
	
	
	private function day_week() {
	    $dw = array();
		
		for($i = -7; $i <= 0; $i++){
			$dw[] = date("D", mktime(0, 0, 0, date('m'), date('d')-($i), date('Y')));
		}
		
	    return $dw;
	}
	
	
	private function get_count($channel, $team)
	{
		$channelEscaped = pg_escape_string($channel);
		$teamEscaped = pg_escape_string($team);
		$sql = $this->get_sql($channelEscaped, $teamEscaped);
	    
	    $result = pg_query($this->conn, $sql);
		return $result;
	}
	
	
	
	private function get_sql($channel, $team, $user = false) {
		$sql = 'select  "dim_team"."nome_team" as "Team",
			"dim_canal"."nome_canal" as "Channel",
			    "user"."nome_user" as "User",
		    -- PODE FILTRAR NO WHERE COM ESSE CAMPO A CHAVE DO USER
			"user"."id_user" as "id_user",
			"dim_tempo"."dia_semana_numero" as "DiaSemana",
			avg(( case when( select sentimento from dim_contexto_mining cm where factvw.id_dim_sentimento_mining = cm.id_dim_sentimento_mining ) = \'VERY_NEGATIVE\' then -2 when( select sentimento from dim_contexto_mining cm where factvw.id_dim_sentimento_mining = cm.id_dim_sentimento_mining ) = \'NEGATIVE\' then -1 when( select sentimento from dim_contexto_mining cm where factvw.id_dim_sentimento_mining = cm.id_dim_sentimento_mining) = \'NEUTRAL\' then 0  when( select sentimento from dim_contexto_mining cm  where factvw.id_dim_sentimento_mining = cm.id_dim_sentimento_mining  ) = \'POSITIVE\' then 1 when( select sentimento  from dim_contexto_mining cm  where factvw.id_dim_sentimento_mining = cm.id_dim_sentimento_mining) = \'VERY_POSITIVE\' then 2  end)) as "valor"

		    from    "dim_canal" as "dim_canal",
			"public"."fato_slack" as "factvw",
			"dim_user" as "user",
			"dim_team" as "dim_team",
			"dim_tempo" as "dim_tempo"

		    where   "factvw"."id_dim_canal" = "dim_canal"."id_dim_canal"';
		
			if($user)
				$sql .= sprintf('and "user"."id_user" = \'?\'', $userReq);
			
		    $sql = sprintf('-- CHAVE DO CANAL
			and "dim_canal"."id_canal" = \'?\'
			and "factvw"."id_dim_team" = "dim_team"."id_dim_team"
		    -- CHAVE DO TEAM
			and "dim_team"."id_team" = \'?\'
			and "factvw"."id_dim_tempo" = "dim_tempo"."id_tempo"
			and "user"."id_dim_user" = "factvw"."id_dim_user"
			and "factvw"."id_dim_tempo" in (select id_tempo from dim_tempo where data_tempo <= current_date and data_tempo > (current_date - 7))

		    group by  "dim_team"."nome_team", "dim_canal"."nome_canal","user"."nome_user","user"."id_user", "dim_tempo"."dia_semana_numero"
		    order by "dim_tempo"."dia_semana_numero"', $channel, $team);
			
			return $sql;
	}

}