<?php
/*
|--------------------------------------------------------------------------
|  Handle sitemap generation
|--------------------------------------------------------------------------
*/	
class CMC_Sitemaps
{

/*
|--------------------------------------------------------------------------
|  Create Coin Market Cap sitemap Upload / Download Directories on Plugin Activate
|--------------------------------------------------------------------------
*/
public static function cmc_generate_sitemap(){
        $upload = wp_upload_dir();
        $upload_dir = $upload['basedir'];
        $upload_dir = $upload_dir . '/cmc';
        $sitemap_dir = $upload_dir . '/cmc/sitemap';

        if (! is_dir($upload_dir)) {
            mkdir( $upload_dir );           
        }
        if(! is_dir($upload_dir.'/sitemap')){
            mkdir( $upload_dir.'/sitemap' );
        }

		$fetch_coins = 500;
		$start_point = 0;
		$old_currency="USD";
		$cmc_coins_page=1;
		$response=array();
		$per_page =50;
        $cmcDB = new CMC_Coins;
		$first_500_coin_data = $cmcDB->get_coins( array("number"=>500,'offset'=> 0));
		$second_500_coin_data = $cmcDB->get_coins( array("number"=>500,'offset'=>499));
		$third_500_coin_data = $cmcDB->get_coins( array("number"=>500,'offset'=> 999));
        $fourth_500_coin_data = $cmcDB->get_coins( array("number"=>500,'offset'=> 1500));
        
        $sitemap_url = home_url('wp-content/uploads/cmc/sitemap/', '/');
        

		if (is_array($first_500_coin_data) && count($first_500_coin_data) > 0) {
			$response['status'] = array('status' => 'Successfully Generated Sitemap',
			'message'=>'Please copy these links and submit to search engines.'
		);
        CMC_Sitemaps::cmc_create_sitemap($first_500_coin_data,1,500);
		CMC_Sitemaps::cmc_create_sitemap($second_500_coin_data,2,500);
		CMC_Sitemaps::cmc_create_sitemap($third_500_coin_data,3,500);
		CMC_Sitemaps::cmc_create_sitemap($fourth_500_coin_data,4,500);

			$combine_sitemap = "<?xml version='1.0' encoding='UTF-8'?>";
			$combine_sitemap .= "<sitemapindex xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>";

				$combine_sitemap .= "<sitemap>";
					$combine_sitemap .=	"<loc>". $sitemap_url . 'sitemap-1.xml' ."</loc>";
					$combine_sitemap .= "</sitemap>";
					$combine_sitemap .= "<sitemap>";
					$combine_sitemap .=	"<loc>". $sitemap_url . 'sitemap-2.xml' ."</loc>";
					$combine_sitemap .= "</sitemap>";
					$combine_sitemap .= "<sitemap>";
					$combine_sitemap .=	"<loc>". $sitemap_url . 'sitemap-3.xml' ."</loc>";
					$combine_sitemap .= "</sitemap>";
					$combine_sitemap .= "<sitemap>";
					$combine_sitemap .=	"<loc>". $sitemap_url . 'sitemap-4.xml' ."</loc>";
					$combine_sitemap .= "</sitemap>";
			$combine_sitemap .= "</sitemapindex>";

			header('content-type: text/xml');
			echo $combine_sitemap;
					die();
		} else {
			$response[] = array('status' => 'Error',
				'error'=> 'API Request Timeout'
			);
			echo $rs = json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
			die();
		}


	}	

/*
|--------------------------------------------------------------------------
| coins sitemap XML generator 
|--------------------------------------------------------------------------
*/    
public static function cmc_create_sitemap($all_coin_data, $cmc_coins_page, $per_page){
        $coins_xml = '';
        $sitemap = '';
        $show_coins = $all_coin_data; 
        $upload = wp_upload_dir();
        $upload_dir = $upload['basedir'];
        $sitemap_dir = $upload_dir . '/cmc/sitemap';

        if (is_array($show_coins) && count($show_coins) > 0) {
            foreach ($show_coins as $coin) {
                $coin = (array)$coin;
                $coin_id = $coin['coin_id'];
                $coin_symbol = $coin['symbol'];
                $single_page_slug = cmc_get_page_slug();
                
                $coin_url = esc_url(home_url($single_page_slug . '/' . $coin_symbol . '/' . $coin_id.'/', '/'));
                $static = '12';
                $coins_xml .= '<url>' .
                    '<loc>' . $coin_url . '</loc>' .
                    '<priority>1</priority>' .
                    '<changefreq>daily</changefreq>' .
                    '</url>';
            }
            $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
            $sitemap .= '<?xml-stylesheet type="text/xsl" href="' . CMC_URL . '/sitemap/sitemap-style.xsl"?>';
            $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
            $sitemap .= $coins_xml;
            $sitemap .= '</urlset>';
            $fp = fopen($sitemap_dir . '/' . "sitemap-". $cmc_coins_page .".xml", 'w');
            fwrite($fp, $sitemap);
            fclose($fp);
        }
}

}