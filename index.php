<?php

    require_once ( "core.php" );
    
    $lab = new Adaptowebs;
    
    $lab -> errors ();

    require_once ( "config.php" );
    
    if (peticion () == 0) {
        
        http_response_code ( 404 );
    
        page (
            
                $lab 
            
            ,
        
                Element::get ( "title" , "adaptowebs" ) 
            
            ,
            
                Element::get ( "h1" , "404 !" ) .
                
                Element::get ( "h2" , "Not Found." ) 
            
                , 'en',''
            
        );
    
    } else {
        
        if ( 
            
            tipo_peticion ( peticion () ) == "GET" ||
            
            tipo_peticion ( peticion () ) == "POST" ||
            
            tipo_peticion ( peticion () ) == "PUT" ||
            
            tipo_peticion ( peticion () ) == "DELETE" 
            
        ) {
            
            switch ( tipo_peticion ( peticion () ) ) {
                
                case "POST" : 
                    
                    $tabla = peticion ()[1];
                    
                    if ( isset ( $_GET['register'] ) && $_GET['register'] == true ) {
                        
                        $tabla = explode("?",$tabla)[0];
                            
                        registrar ( $tabla , $_POST , $conexion , sufijo () , $lab );
                            
                    } else {
                
                        if ( empty (
                            $lab -> columnsData ($conexion,$tabla,iterar_post ($_POST))
                            )
                        ) {
                            
                            echo json_encode ( [
                                "ERROR" => "404 Las Columnas Del Formulario No Coinciden"    
                            ] , http_response_code ( 404 ) );
                            
                        } else {
                            
                            $lab -> postData ( $tabla , columns_values(iterar_post ($_POST),iterar_post_2 ($_POST)) , $conexion );
                            
                        }
                    }
                    
                break;
                
                case "PUT" : 
                    
                    $tabla = peticion ()[1];
                    $tabla = explode("?",$tabla)[0];
                    $lab -> putData ($tabla,$conexion,$lab);
                    
                break;
                
                case "DELETE" : 
                    
                    $tabla = peticion ()[1];
                    $tabla = explode("?",$tabla)[0];
                    $lab -> deleteData ($tabla,$conexion,$lab);
                    
                break;
                
                default:
                    
                    $tabla = explode ( "?" , peticion ()[1] )[0];
                    
                    if ( isset ( $_GET['key'] ) && isset ( $_GET['value'] ) && !isset ( $_GET['rel'] ) && !isset ( $_GET['type'] )) {
                        
                        $lab -> getDataFilter ( $tabla , select () , $_GET['key'] , $_GET['value'] , orderby () , ordermode () , startat () , endat () , $conexion , $lab );
                        
                    } else if ( isset ( $_GET['rel'] ) && isset ( $_GET['type'] ) && $tabla == "relations" && !isset ( $_GET['key'] ) && !isset ( $_GET['value'] )) {
                        
                        $lab -> getTablesData ( $_GET['rel'] , $_GET['type'] , select () , orderby () , ordermode () , startat () , endat () , $conexion , $lab );
                        
                    } else if ( isset ( $_GET['rel'] ) && isset ( $_GET['type'] ) && $tabla == "relations" && isset ( $_GET['key'] ) && isset ( $_GET['value'] )) {
                        
                        $lab -> getTablesDataFilter ( $_GET['rel'] , $_GET['type'] , select () , $_GET['key'] , $_GET['value'] , orderby () , ordermode () , startat () , endat () , $conexion , $lab );
                        
                    } else if ( !isset ( $_GET['rel'] ) && !isset ( $_GET['type'] ) && isset ( $_GET['key'] ) && isset ( $_GET['search'] ) ) {
                        
                        $lab -> getDataSearch ( $tabla , select () , $_GET['key'] , $_GET['search'] , orderby () , ordermode () , startat () , endat () , $conexion , $lab );
                        
                    } else if ( isset ( $_GET['rel'] ) && isset ( $_GET['type'] ) && $tabla == "relations" && isset ( $_GET['key'] ) && isset ( $_GET['search'] )) {
                        
                        $lab -> getTablesDataSearch ( $_GET['rel'] , $_GET['type'] , select () , $_GET['key'] , $_GET['search'] , orderby () , ordermode () , startat () , endat () , $conexion , $lab );
                        
                    } else if ( !isset ( $_GET['rel'] ) && !isset ( $_GET['type'] ) && isset ( $_GET['key'] ) && isset ( $_GET['betweenstart'] ) && isset ( $_GET['betweenend'] ) ) {
                        
                        $lab -> getDataRange ( $tabla , select () , $_GET['key'] , $_GET['betweenstart'] , $_GET['betweenend'] , orderby () , ordermode () , startat () , endat () , filterto () , into () , $conexion , $lab );
                        
                    } else if ( isset($_GET['rel']) && isset($_GET['type']) && $tabla == "relations" && isset ( $_GET['key'] ) && isset ( $_GET['betweenstart'] ) && isset ( $_GET['betweenend'] ) ) {
                        
                        $lab -> getTablesDataRange ( $_GET['rel'] , $_GET['type'] , select () , $_GET['key'] , $_GET['betweenstart'] , $_GET['betweenend'] , orderby () , ordermode () , startat () , endat () , filterto () , into () , $conexion , $lab );
                        
                    } else {
                        
                        $lab -> getData ( $tabla , select () , orderby () , ordermode () , startat () , endat () , $conexion , $lab );   
                        
                    }
                
            }
            
        } else {
            
            http_response_code ( 404 );
            
            echo "404 METHOD NOT FOUND.";
            
        }
        
    }

?>
