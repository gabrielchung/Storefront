<?php

//
//
// TO-DO: Add session token to avoid people using this as an api
//
// But they can use a robot to load the page and use this function as an api anyway. Umm...
//
//

$refuseCall = false;

if (isset($_GET['action'])) {

    $action = transform_action($_GET['action']);

    if (empty($action)) {

        $refuseCall = true;

    } else {

        get_data_from_backend($action);

    }

} else {

    $refuseCall = true;

}

if ($refuseCall) {

    echo 'null';

}

function transform_action($get_action) {
    switch ($get_action) {
        case 'products':
            return 'getProducts';
            break;
        
        case 'featured':
            return 'getFeaturedProductIDs';
            break;

        case 'categories':
            return 'getCategories';
            break;

        case 'catalog':
            return 'getCatalog';
            break;

        case 'image':
            return 'getImage';
            break;

        default:
            return '';
            break;
    }
}

function get_data_from_backend($action) {

    $ch = curl_init();

    // GET paramters
    $data = array('name' => 'storefront', 'password' => '');
    $data['action'] = $action;
    pass_get_parameters_to_backend($data);
    $getParameters = http_build_query($data);

    //curl_setopt($ch, CURLOPT_URL, 'https://betterworld.company/lab/bizsys/api/api.php?name=storefront&password=storefrontForJesus');
    curl_setopt($ch, CURLOPT_URL, 'https://betterworld.company/lab/bizsys/api/api.php?' . $getParameters);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);

    if ('getImage' === $data['action']) {

        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

        header('Content-type: '.$contentType);

    } else {

        //Used plain text instead of JSON. May change in the future.

        //for normal data output (JSON)
        //header('Content-Type: application/json');

    }
        
    echo $response;

    curl_close($ch);

}

function pass_get_parameters_to_backend(&$data) {

    foreach ($_GET as $getKey => $getValue) {

        if ($getKey !== 'action') {

            $data[$getKey] = $getValue;

        }

    }

}

?>