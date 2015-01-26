<?php


function get_contents($cur_url,$depth,$links){
	// echo PHP_EOL.print_r('---------------------------------',true).PHP_EOL;
	// print_r('depth: '.$depth);
	// print_r($links);
	// echo PHP_EOL.print_r('---------------------------------',true).PHP_EOL;

	$html = file_get_contents($cur_url);
	$html = phpQuery::newDocument($html);

	$a = pq('a');

	for($i=0;$i<count($a);$i++){

		$link = $a->eq($i)->attr('href');

		if($link == '#'){
			continue;
		}


		$link = get_url($link,$cur_url);


		if(in_array($link, $links)){
			continue;
		}

		$links[] = $link;
		echo print_r($link,true).PHP_EOL;

		if(($depth)>0){
			
			$links = get_contents(
							$link, 
							$depth-1, 
							$links
						);
		}


	}


	return $links;

}


function get_url($child_url,$parent_url){

	$parsed_child_url = parse_url($child_url);
	$parsed_parent_url = parse_url($parent_url);

	//scheme
	$url = ((empty($parsed_child_url['scheme']))? $parsed_parent_url['scheme'] : 'http' );

	//host
	$url = $url.'://'.((empty($parsed_child_url['host'])) ? 
							$parsed_parent_url['host'] : 
							$parsed_child_url['host']
						);

	//path
	if(!empty($parsed_child_url['path'])){


		$parsed_child_url['path'] = (substr($parsed_child_url['path'],0,1)==='/')?
														$parsed_child_url['path']:
														'/'.$parsed_child_url['path'];


		$url = $url.((empty($parsed_child_url['path'])) ? 
								'' : 
								$parsed_child_url['path']
							);
	}

	//query
	$url = $url.((empty($parsed_child_url['query']))? 
							'' : 
							'?'.$parsed_child_url['query']
						);

	return $url;
}

