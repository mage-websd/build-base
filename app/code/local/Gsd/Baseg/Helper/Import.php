<?php
    $store_id = 1;
    $csv_filepath = Mage::getBaseDir('skin').'/frontend/default/default/unika_subscribers.csv';
    $csv_delimiter = ',';
    $csv_enclosure = '"';
    /*$magento_path = __DIR__;
    require "{$magento_path}/app/Mage.php";*/

    //Mage::app()->setCurrentStore($store_id);
    /*echo "<pre>";
    print_r($csv_filepath);echo '<br/>';*/
    $fp = fopen($csv_filepath, "r");

    if (!$fp) die("{$csv_filepath} not found\n");
    $count = 0;

    while (($row = fgetcsv($fp, 0, $csv_delimiter, $csv_enclosure)) !== false){
        $arrayData = trim($row[0]);
        $arrayData = explode(';', $arrayData);
        if(isset($arrayData[0])) {
            $email = trim($arrayData[0]);
        }
        else {
            $email = '';
        }
        if(isset($arrayData[1])) {
            $name = trim($arrayData[1]);
            if($pos = stripos($name, ' ')) {
                $fname = trim(substr($name, 0,$pos));
                $lname = trim(substr($name, $pos));
            }
            else {
                $fname = $lname = $name;
            }
        }
        else {
            $fname = $lname = '';
        }
        $type = 1;
        $status = 1;
        $website = 1;
        $store = 1;
        $store_view = 1;

        if (strlen($email) == 0) continue;
        $subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($email);
        if ($subscriber->getId()){
            echo $email . " <b>already subscribed</b>\n";
            continue;
            }

        Mage::getModel('newsletter/subscriber')->setImportMode(true)->subscribe($email);
        $subscriber_status = Mage::getModel('newsletter/subscriber')->loadByEmail($email);

        if ($status == 1){
              $subscriber_status->setStatus(Mage_Newsletter_Model_Subscriber::STATUS_SUBSCRIBED);
              $subscriber_status->save();
            }else if($status == 2){
              $subscriber_status->setStatus(Mage_Newsletter_Model_Subscriber::STATUS_NOT_ACTIVE);
              $subscriber_status->save();
            }else if($status == 3){
              $subscriber_status->setStatus(Mage_Newsletter_Model_Subscriber::STATUS_UNSUBSCRIBED);
              $subscriber_status->save();
            }else if($status == 4){
              $subscriber_status->setStatus(Mage_Newsletter_Model_Subscriber::STATUS_UNCONFIRMED);
              $subscriber_status->save();
            }
            echo $email . " <b>ok</b>\n";
        $count++;
    }

echo "Import finished\n";
