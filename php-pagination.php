<?php

function paginate ($total, $per_page, $curr_page)
{
    $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    $result = [];
    $result['html'] = '<div class="pagination">';
    $result['offset'] = ($curr_page - 1) < 0 ? 0 : ($curr_page - 1) * $per_page ;
    
    $numOfPage = ceil($total / $per_page);
    if($numOfPage > 1)
    {
        if($numOfPage < 7 )
        {
            for($i = 1; $i <= $numOfPage; $i++ )
            {
                if($i == $curr_page)
                $result['html'] .= '<a class="active">'.$i.'</a>';
                else
                $result['html'] .= '<a href="'.changeValOfUrl('page', $i).'">'.$i.'</a>';
            }
        }
        else
        {
            //- if the page is in middle
            if(($curr_page-3) >= 1 && ($curr_page+3) <= $numOfPage)
            {
                $from = $curr_page - 3;
                $to = $curr_page + 3;
                for ($i=$from; $i <= $to ; $i++) { 
                    if($i == $curr_page)
                    $result['html'] .= '<a class="active">'.$i.'</a>';
                    else
                    $result['html'] .= '<a href="'.changeValOfUrl('page', $i).'">'.$i.'</a>';
                }

            }
            //- if the page is close to last page
            else if(($curr_page-3) >= 1 && ($curr_page+3) >= $numOfPage)
            {
                $from = $curr_page - 3;
                $to = ($curr_page + 3) > $numOfPage ? $numOfPage : ($curr_page + 3);
                for ($i=$from; $i <= $to ; $i++) { 
                    if($i == $curr_page)
                    $result['html'] .= '<a class="active">'.$i.'</a>';
                    else
                    $result['html'] .= '<a href="'.changeValOfUrl('page', $i).'">'.$i.'</a>';
                }

            }
            //- if the page is close to first page
            else if(($curr_page-3) < 1 && ($curr_page+3) <= $numOfPage)
            {
                $from = ($curr_page - 3) < 1 ? 1 : ($curr_page - 3);
                $to = $curr_page + 3;
                for ($i=$from; $i <= $to ; $i++) { 
                    if($i == $curr_page)
                    $result['html'] .= '<a class="active">'.$i.'</a>';
                    else
                    $result['html'] .= '<a href="'.changeValOfUrl('page', $i).'">'.$i.'</a>';
                }

            }

        }
    }

    $from = ($total == 0) ? 0 : ($result['offset'] + 1);

    $result['html'] .= '</div>';
    $result['html'] .= 'Showing '.$from.' to '. (($result['offset']+$per_page) < $total ? ($result['offset']+$per_page) : $total ).' of '. $total;
    return $result;
}

//- URL creator for the pagination "page" variable
function changeValOfUrl($var, $val)
{   
    //------ this function only belong to 'paginate' function ----
    $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    
    $pathInfo = parse_url($url);
    $queryString = isset($pathInfo['query']) ? $pathInfo['query'] : ''; 
    
    // convert the query parameters to an array
    parse_str($queryString, $queryArray);
    
    // add the new query parameter into the array
    $queryArray[$var] = $val;
    
    // build the new query string
    $newQueryStr = http_build_query($queryArray);
    
    return '?'.$newQueryStr;
}


//- How to use
    $page = false;
    $limit = 15; //- set the limit per page
    $offset = 0;
    $total = $num_of_rows; //- get the number of rows from DB in any way
    if(isset($_GET['page']))
    {
        $page = is_numeric($_GET['page']) ? $_GET['page'] : 0;
    }else
    {
        $page = 1;
    }

    $paginate = paginate($total, $limit, $page);
    $offset = $paginate['offset'];
    
    /* 
    1. return the html pagination buttons if needed or return empty string. 
    2. then echo it in the HTML
    3. pls edit the paginate function above to change the HTML template as you like
    */
    $pagination_html = $paginate['html']; 

    //- then use $limit and $offset in the SQL query
    $sql = "SELECT * FROM posts limit $offset, $limit";
    $stmt = $conn->prepare($sql);
    //-- and do whatever you want


