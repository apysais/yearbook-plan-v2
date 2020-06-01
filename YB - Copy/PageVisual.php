<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Page Visual.
 * @since 0.0.1
 * */
class YB_PageVisual {
  /**
	 * instance of this class
	 *
	 * @since 0.0.1
	 * @access protected
	 * @var	null
	 * */
	protected static $instance = null;

	/**
	 * Return an instance of this class.
	 *
	 * @since     0.0.1
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		/*
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}


	public function __construct(){

	}

  /**
  * Create page visual.
  * @param  $data_args  array {
  *   @type int  @size the size of the page
  * }
  **/
  public function init($data_args = [])
	{
		/**
		* cs = current size
		* lpp = last previous page
		* csp = current start page
		* mcs = mid current size (loop, this is more than 1 page occupy)
		* clp = current last page
		* wp = whole page (1)
		* tcsp = total current size page (lpp + cs), this will loop the page if cs is > wp
		**/

		//whole page
		$wp = 1;

		$index = 0;
		if(isset($data_args['index'])){
			$index = $data_args['index'];
		}

		//current size
		$cs = 0;
		if(isset($data_args['cs'])){
			$cs = $data_args['cs'];
		}

		//current page
		$page = 1;
		if(isset($data_args['page'])){
			$page = $data_args['page'];
		}

		//current start page
		$csp = 0;
		if(isset($data_args['csp'])){
			$csp = $data_args['csp'];
		}

		//middle current start
		$mcs = 0;
		if(isset($data_args['mcs'])){
			$mcs = $data_args['mcs'];
		}

		//current last page
		$clp = 0;
		if(isset($data_args['clp'])){
			$clp = $data_args['clp'];
		}

		//last previous page
		$lpp = 0;
		if(isset($data_args['lpp'])){
			$lpp = $data_args['lpp'];
		}

		if($index == 0){
	    $page = 1;
	    $csp = 0;
	    $mcs = 0;
	    $clp = 0;
	    $lpp = 0;
	  }

		$tcsp = ($lpp + $cs);

		if($tcsp > $wp){
	    //check if the current visual is in one page only or will have more pages
	    if($lpp == $wp){
	      //is the last previous page ate up the whole page?
	      if($cs > $wp){
	        //create multiple pages
	        $csp = 1;
	        $mcs = ($cs - $csp);
	        if(yb_is_whole_number($mcs)){
	          $clp = 1;
	        }else{
	          $clp = yb_get_decimal_part($mcs);
	        }
	      }else{
	        $mcs = 1;
	        if($lpp == 1){
	          $csp = $cs;
	        }else{
	          $csp = ($wp - $cs);
	        }

	        if($csp == 0){
	          $csp = 1;
	        }
	        if($csp > $cs){
	          $mcs = floor($csp - $cs);
	        }else{
	          $mcs = floor($cs - $csp);
	        }

	        $clp = $csp;
	      }
	    }else{
	      $csp = (1 - $lpp);
	      $mcs = ($cs - $csp);
	      if($mcs > 1){
	        $mcs = floor($mcs);
	      }
	      if($cs > $wp){
	        $clp = yb_get_decimal_part($cs - $csp);
	        if($clp == 0){
	          $clp = 1;
	        }
	      }else{
	        $clp = $mcs;
	      }

	    }
	  }else{
	    //meaning we occupy the current page only
	    $csp = $cs;
	    $mcs = $cs;
	    $clp = $tcsp;
	    if($cs <= $wp){
	      $mcs = 0;
	    }
	  }

		$ret = [
			'wp' 		=> $wp,
		  'page' 	=> $page,
		  'cs' 		=> $cs,
		  'lpp' 	=> $lpp,
		  'tcsp' 	=> $tcsp,
		  'csp' 	=> $csp,
		  'mcs' 	=> $mcs,
		  'clp' 	=> $clp
		];

		return $ret;

  }

	public function htmlPageVisual($args=[])
	{
	  $str = '';

	  $visual_arr = [];

	  $wp                   = $args['wp'];
	  $cs                   = $args['cs'];
	  $lr                   = $args['lr'];
	  $lpp                  = $args['lpp'];
	  $tcsp                 = $args['tcsp'];
	  $csp                  = $args['csp'];
	  $mcs                  = $args['mcs'];
	  $clp                  = $args['clp'];
	  $page                 = $args['page'];

	  $next_page = ($lr == 'left' ? 'right':'left');

	  $page_id = 0;

	  $page_index_lr = 0;
	  if($lr == 'right'){
	    $page_index_lr = 1;
	  }

	  if($lpp == 1){
	    $visual_arr[] = [
	      'side' => $lr,
	      'lpp' => 0,
	      'csp' => $csp,
	      'mcs' => 0,
	      'clp' => 0,
	    ];
	  }else{
	    $visual_arr[] = [
	      'side' => $lr,
	      'lpp' => $lpp,
	      'csp' => $csp,
	      'mcs' => 0,
	      'clp' => 0,
	    ];
	  }

	  if($mcs > 1){
	    for($i = 1; $i <= $mcs; $i++){
	      $mid_mcs_lr = $this->leftOrRight($page_index_lr);
	      $visual_arr[$i] = [
	        'side' => $mid_mcs_lr,
	        'loop-mcs' => 1,
	        'lpp' => 0,
	        'csp' => 0,
	        'clp' => 0,
	        'mcs' => 1,
	      ];
	      $page_index_lr++;
	    }
	    $mid_next_page = ($mid_mcs_lr == 'left' ? 'right':'left');
	    if($clp < 1){
	      $visual_arr[$i] = [
	        'side' => $mid_next_page,
	        'loop-mcs-clp' => $clp,
	        'clp' => $clp,
	        'csp' => 0,
	        'mcs' => 0,
	        'lpp' => 0
	      ];
	    }
	  }else{
	    $visual_arr[] = [
	      'side' => $next_page,
	      'mcs' => $mcs,
	      'csp' => 0,
	      'lpp' => 0,
	      'clp' => 0,
	      'mid-mcs' => $mcs,
	    ];

	    if($clp < 1 && ($mcs != $clp) && $mcs != 0){
	      $next_page = ($lr == 'left' ? 'left':'right');
	      $visual_arr[] = [
	        'side' => $next_page,
	        'mid-mcs-clp' => $clp,
	        'clp' => $clp,
	        'csp' => 0,
	        'lpp' => 0,
	        'mcs' => 0,
	      ];
	    }
	  }

	  if($lr == 'right'){
	    $left_unshift = [
	      'csp' => 0,
	      'lpp' => 0,
	      'mcs' => 0,
	      'clp' => 0,
	      'side' => 'left',
	      'empty' => 'y'
	    ];
	    array_unshift($visual_arr, $left_unshift);
	  }

	  $last_array = count($visual_arr);
	  if($last_array % 2 == 1){
	    $right_end = [
	      'csp' => 0,
	      'lpp' => 0,
	      'mcs' => 0,
	      'clp' => 0,
	      'side' => 'right',
	      'empty' => 'y'
	    ];
	    array_push($visual_arr, $right_end);
	  }

	  /*echo '<pre>';
	  print_r($visual_arr);
	  echo '</pre>';*/

	  $sum_visual_last_second_array = '-1';
	  $sum_visual_last_array = '-1';
	  if(count($visual_arr) > 2){
	    $visual_last_array = (count($visual_arr) - 1);
	    $visual_last_second_array = (count($visual_arr) - 2);

	    $sum_visual_last_second_array = ($visual_arr[$visual_last_second_array]['lpp'] + $visual_arr[$visual_last_second_array]['csp'] + $visual_arr[$visual_last_second_array]['mcs'] + $visual_arr[$visual_last_second_array]['clp']);
	    $sum_visual_last_array = ($visual_arr[$visual_last_array]['lpp'] + $visual_arr[$visual_last_array]['csp'] + $visual_arr[$visual_last_array]['mcs'] + $visual_arr[$visual_last_array]['clp']);
	  }

	  $chunk_visual = array_chunk($visual_arr, 2);

	  if($sum_visual_last_second_array == 0 && $sum_visual_last_array == 0){
	    $chunk_visual_last_array =  (count($chunk_visual) - 1);
	    unset($chunk_visual[$chunk_visual_last_array]);
	  }

	  /*
	  echo '<pre>';
	  print_r($chunk_visual);
	  echo '</pre>';*/

	  $visual_style = "<div class='containerx page-visual'>";

	  foreach($chunk_visual as $k => $v){
	    $visual_style .= "<div class='row bg-dark ".$k."'>";
	    if(is_array($v)){
	      $style_lpp = '';
	      $style_csp = '';
	      $style_mcs = '';
	      $style_clp = '';
	      foreach($v as $k_v => $v_val){
	        $lpp_hun = (100 * $v_val['lpp']);
	        $csp_hun = (100 * $v_val['csp']);
	        $mcs_hun = (100 * $v_val['mcs']);
	        $clp_hun = (100 * $v_val['clp']);

	        $style_lpp = "<div class='bg-info' style='height:{$lpp_hun}px'> </div>";
	        $style_csp = "<div class='bg-success' style='height:{$csp_hun}px'> </div>";
	        $style_mcs = "<div class='bg-success' style='height:{$mcs_hun}px'> </div>";
	        $style_clp = "<div class='bg-success' style='height:{$clp_hun}px'> </div>";

	        $visual_style .= "<div class='col-md-6 $k_v ".$v_val['side']." page-row'>";
	        $visual_style .= "$style_lpp";
	        $visual_style .= "$style_csp";
	        $visual_style .= "$style_mcs";
	        $visual_style .= "$style_clp";
	        $visual_style .= "</div>";

	      }
	    }
	    $visual_style .= "</div>";
	  }

	  $visual_style .= "</div>";

	  return $visual_style;

	}

	public function leftOrRight($current_page){
	  $current_page = floor($current_page);
	  if($current_page % 2 == 0){
	    return 'right';
	  }else{
	    return 'left';
	  }
	}

}
