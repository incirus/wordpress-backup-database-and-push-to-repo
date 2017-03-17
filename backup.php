<?php
echo '<pre>';
//ENTER THE RELEVANT INFO BELOW
$mysqlUserName      = "root";
$mysqlPassword      = "yardimci2";
$mysqlHostName      = "localhost";
$DbName             = "dtv_blog";
$backup_name        = "dtv_blog.sql";

Export_Database($mysqlHostName,$mysqlUserName,$mysqlPassword,$DbName,  $tables=false, $backup_name=false );

function Export_Database($host,$user,$pass,$name,  $tables=false, $backup_name=false )
{
    $mysqli = new mysqli($host,$user,$pass,$name);
    $mysqli->select_db($name);
    $mysqli->query("SET NAMES 'utf8'");

    $queryTables    = $mysqli->query('SHOW TABLES');
    while($row = $queryTables->fetch_row())
    {
        $target_tables[] = $row[0];
    }
    if($tables !== false)
    {
        $target_tables = array_intersect( $target_tables, $tables);
    }
    foreach($target_tables as $table)
    {
        $result         =   $mysqli->query('SELECT * FROM '.$table);
        $fields_amount  =   $result->field_count;
        $rows_num=$mysqli->affected_rows;
        $res            =   $mysqli->query('SHOW CREATE TABLE '.$table);
        $TableMLine     =   $res->fetch_row();
        $content        = (!isset($content) ?  '' : $content) . "\n\n".$TableMLine[1].";\n\n";

        for ($i = 0, $st_counter = 0; $i < $fields_amount;   $i++, $st_counter=0)
        {
            while($row = $result->fetch_row())
            { //when started (and every after 100 command cycle):
                if ($st_counter%100 == 0 || $st_counter == 0 )
                {
                        $content .= "\nINSERT INTO ".$table." VALUES";
                }
                $content .= "\n(";
                for($j=0; $j<$fields_amount; $j++)
                {
                    $row[$j] = str_replace("\n","\\n", addslashes($row[$j]) );
                    if (isset($row[$j]))
                    {
                        $content .= '"'.$row[$j].'"' ;
                    }
                    else
                    {
                        $content .= '""';
                    }
                    if ($j<($fields_amount-1))
                    {
                            $content.= ',';
                    }
                }
                $content .=")";
                //every after 100 command cycle [or at last line] ....p.s. but should be inserted 1 cycle eariler
                if ( (($st_counter+1)%100==0 && $st_counter!=0) || $st_counter+1==$rows_num)
                {
                    $content .= ";";
                }
                else
                {
                    $content .= ",";
                }
                $st_counter=$st_counter+1;
            }
        } $content .="\n\n\n";
    }

    file_put_contents('dtv_sql/'.$backup_name, $content);
    echo "DB export done!\n";
}

// push to the bitbucket repo
exec('git add -A');
exec('git commit -m"auto push - '. date("Y-m-d-H-i-s").'"');
exec('git push -u origin master');

echo 'Repo UPDATED!</pre>';
?>