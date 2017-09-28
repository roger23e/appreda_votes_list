<?php 

    include './Configuracion.php';

    $m = $_GET['m'];

    if ($m == "LEER_VOTANTES_CASILLA")
    {
        $v              = $_GET['v'];
        $valor          = explode("|", $v);
        $COD_OPERACION  = $valor[0];
        $COD_ENTIDAD    = $valor[1];
        $COD_MUNICIPIO  = $valor[2];
        $COD_SECCION    = $valor[3];
        $COD_CASILLA    = $valor[4];

        $conexion = mysqli_connect($server, $user, $pass, $bd) 
        or die("Ha sucedido un error inexperado en la conexion de la base de datos");
        mysqli_set_charset($conexion, "utf8");
    
    
        $sql = "SELECT
                    CONSECUTIVO 
                FROM 
                    operaciones_electores 
                WHERE 
                    COD_OPERACION   = '".$valor[0]."'
                AND COD_ENTIDAD     = '".$valor[1]."'
                AND COD_MUNICIPIO   = '".$valor[2]."'
                AND COD_SECCION     = '".$valor[3]."'
                AND COD_CASILLA     = '".$valor[4]."'
                AND VOTO            = 1
                ORDER BY 
                    CONSECUTIVO;";
        ///ECHO $sql;

        $array = array();
        if(!$result = mysqli_query($conexion, $sql));
        $num_fila = $result->num_rows;

        if ($num_fila === 0)
        {
            $array[] = array('RESULTADO'=> "0002",'MENSAJE'=> "NO SE ENCONTRO INFORMACION");
        }
        else 
        {
            $array[] = array
            (
                'RESULTADO'     => "0000", 
                'MENSAJE'       => "VOTANTES ENCONTRADOS"
            );



            while($row = mysqli_fetch_array($result)) 
            { 
                $CONSECUTIVO    = $row['CONSECUTIVO'];

                $array[] = array
                (
                    'CONSECUTIVO'   => $CONSECUTIVO
                );

            }
        }

        $close = mysqli_close($conexion) 
                
        or die("Ha sucedido un error inexperado en la desconexion de la base de datos");

        if(isset($_GET["callback"]))
        {	
                echo $_GET["callback"]."(" . json_encode($array) . ");";	
        }
        else
        {
            echo  json_encode($array);
        } 
    }
    
    if ($m == "LOGIN_LIST")
    {
        $v      = $_GET['v'];

        $valor  = explode("|", $v);

        $conexion = mysqli_connect($server, $user, $pass, $bd) 
        or die("Ha sucedido un error inexperado en la conexion de la base de datos");
        mysqli_set_charset($conexion, "utf8");

        $sql = "SELECT
                    usuario,
                    COD_OPERACION,
                    COD_ENTIDAD,
                    COD_MUNICIPIO,
                    COD_SECCION,
                    COD_CASILLA 
                FROM 
                    operaciones_usuarios_casillas 
                WHERE 
                    usuario     = '".$valor[0]."'
                AND clave_list  = '".$valor[1]."'";
      
        

        $usuarios = array(); //creamos un array

        if(!$result = mysqli_query($conexion, $sql)) die();

        $num_fila = $result->num_rows;

        if ($num_fila === 0)
        {
            $usuarios[] = array('RESULTADO'=> "0001",'MENSAJE'=> "USUARIO NO ENCONTRADO");
        }
        else 
        {
            while($row = mysqli_fetch_array($result)) 
            { 
                $usuario=$row['usuario'];
                $operacion=$row['COD_OPERACION'];
                $casilla=$row['COD_CASILLA'];
                $entidad=$row['COD_ENTIDAD'];
                $municipio=$row['COD_MUNICIPIO'];
                $seccion=$row['COD_SECCION'];
                
                $usuarios[] = array
                (
                    'RESULTADO'=> "0000",
                    'MENSAJE'=> "ENCUESTADOR ENCONTRADO", 
                    'usuario'=> $usuario,
                    'COD_OPERACION'=> $operacion,
                    'COD_CASILLA'=> $casilla,
                    'COD_ENTIDAD'=> $entidad,
                    'COD_MUNICIPIO'=> $municipio,
                    'COD_SECCION'=> $seccion
                );
            }
        }

        $close = mysqli_close($conexion) 
        or die("Ha sucedido un error inexperado en la desconexion de la base de datos");

        if(isset($_GET["callback"]))
        {	
                echo $_GET["callback"]."(" . json_encode($usuarios) . ");";	
        }
        else
        {
            echo  json_encode($usuarios);
        }   
    }
?>