<?php
/*

Copy All the Files from a directory with php

*/

$new = "/covid-19-7";

  // copy a directory and all subdirectories and files (recursive)
  // void dircpy( str 'source directory', str 'destination directory' [, bool 'overwrite existing files'] )

function dircpy($basePath, $source, $dest, $overwrite = false)
{
    if(!is_dir($basePath . $dest)) //Lets just make sure our new folder is already created. Alright so its not efficient to check each time... bite me
    mkdir($basePath . $dest);

    if($handle = opendir($basePath . $source))
    {        
        // if the folder exploration is sucsessful, continue
        while(false !== ($file = readdir($handle)))
        { 
            // as long as storing the next file to $file is successful, continue
            if($file != '.' && $file != '..'){
                $path = $source . '/' . $file;
                if(is_file($basePath . $path)){
                    if(!is_file($basePath . $dest . '/' . $file) || $overwrite)
                    if(!@copy($basePath . $path, $basePath . $dest . '/' . $file)){
                        echo '<font color="red">File ('.$path.') could not be copied, likely a permissions problem.</font>';
                    }
                } 
                else
                if(is_dir($basePath . $path))
                {
                    if(!is_dir($basePath . $dest . '/' . $file))
                    mkdir($basePath . $dest . '/' . $file); 
                    
                    // make subdirectory before subdirectory is copied
                    dircpy($basePath, $path, $dest . '/' . $file, $overwrite); //recurse!
                }
            }
        }

        closedir($handle);
    }
}


//- Run the Function
dircpy(
    getcwd(), //- Base path
    '/admin', //- pass the new directory name (Destination)
    $new //- The folder path which it will copy from
); 
