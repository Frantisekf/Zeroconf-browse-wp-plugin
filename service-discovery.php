<?php

/**
 * Plugin Name: Service discovery
 * Plugin URI: none
 * Description: service discovery plugin
 * Version: 1.0
 * Author: Frantisek Farkas
 * Author URI: none
 */
if (!defined('ABSPATH')) {
    die;
}

function insert_jquery(){
    wp_enqueue_script('jquery-ui-core');// enqueue jQuery UI Core
    wp_enqueue_script('jquery-ui-tabs');// enqueue jQuery UI Tabs

 }
 add_filter('wp_enqueue_scripts','insert_jquery');

add_action('wp_footer', 'layout');
function layout() {
    echo '<div" id="container">
                <h3>Services</h3>
                <div id="tabs">    
                    <ul id="nav-tab-wrapper"></ul>
                </div>
            </div>
            
          </div>';
    
}

function layout_css() {
	echo "
	<style type='text/css'>
    #container {
        width: 100%;
        max-width: 1200px;
    }
    #tabs {
    
    }
    #nav-tab-wrapper {
        list-style: none;
        padding-left: 0;
        float: left;
        width: 30%;
        height: 100%;
        display: inline;
      }
      
	.tab-name {
		padding: 2rem;
        background: #a1a6ad;
        border: 1px solid #83868a;
        cursor: pointer;

	}
    .tab-content {
        padding: 2rem;
        overflow:hidden;
        background-color: #f2f2f2;
        box-shadow: 1px 2px 6px rgb(0 0 0 / 50%);
        margin: 5px;
        cursor: pointer;
        transition: transform .3s ease;
        
    }
    h3 {
        font-size: 30px;
        font-weight: 400;
        color: #1a1a1a;
        margin: 20px;
    }
  

    .nav-tab {
        padding: 1.5em;
        cursor: pointer;
        transition: transform .3s ease;
        background-color: #f2f2f2;
        box-shadow: 1px 2px 6px rgb(0 0 0 / 50%);
        cursor: pointer;
        transition: transform .3s ease;
     }
    .nav-tab:hover {
        border-color: #da2a1d
    }

    .nav-tab-active {
        border: 3px solid #f00;
    }
      
    .tab-content {
        display: none;
    }
      
    .tab-content.active {
        display: block;
        
    }

    .tab-content-inner {
        display:flex;
        flex-direction: column;
        
    }

    .tab-content-inner span {
        margin: 30px 20px 5px;
        border-bottom: 1px solid #979797;
        padding: 10px;
        justify-content: space-between;
    }


	</style>
	";
}

add_action( 'wp_footer', 'layout_css' );



add_action('wp_footer', 'service_browse_ajax');
function service_browse_ajax() {
?>

<script type="text/javascript" >

jQuery(document).ready(function($) {
   
    
    $.ajax({
            type: "GET",
            url: "http://127.0.0.1:80/services",
            dataType: "json",
            crossDomain: true,
            success: browseCallback,
            error: function (error) {
                    console.log(error);
                }      
            });
        });
     
        function browseCallback(data) {
                localStorage.setItem("result", data);
                var obj = data.services;
                    
                $.each( obj, function( i, value ) {
                    var tmp = '';
                    tmp += '    <li href="#tab-'+ i + '" id="tab'+ i +'" class="nav-tab">';
                    tmp += '      <h5>' + value.name + '</h5>';
                    tmp += '    </li>';
                    
                    $('#nav-tab-wrapper').append(tmp);

                    var tmpContent = '';
                    tmpContent += '    <div id="tab-'+i +'" class="tab-content" data-tab-index="'+i+'" >';
                    tmpContent += '      <div class="tab-content-inner">                                             ';
                    tmpContent += '      <span><b>Name</b>: ' + value.name + '</span>';
                    tmpContent += '      <span><b>Type</b>: ' + value.type + '</span>';
                    tmpContent += '      <span><b>Addresses</b>: ' + value.addresses + '</span>';
                    tmpContent += '      <span><b>Port</b>: ' + value.port + '</span>';
                    tmpContent += '      <span><b>properties</b>: ' + JSON.stringify(value.properties, null, 4).replace(/\\n/g, "\\n")
                                      .replace(/\\'/g, "\\'")
                                      .replace(/\\"/g, '\\"')
                                      .replace(/\\&/g, "\\&")
                                      .replace(/\\r/g, "\\r")
                                      .replace(/\\t/g, "\\t")
                                      .replace(/\\b/g, "\\b")
                                      .replace(/\\f/g, "\\f")+ '</div>';
                    tmpContent += '    </div>';
                    
                    $('#tabs').append(tmpContent);

                });
                $('.nav-tab').click(function(e) {

        $(this).addClass('nav-tab-active').siblings().removeClass('nav-tab-active');

        //Toggle target tab
        $($(this).attr('href')).addClass('active').siblings().removeClass('active');
    });
            }
   


</script>


<?php
}
