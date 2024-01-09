<?php

include_once __DIR__.'/vendor/autoload.php';

use Google\Cloud\BigQuery\BigQueryClient as BigQueryClient;
use Google\Cloud\Core\ExponentialBackoff;
use Google\Service\Exception as Google_Service_Exception;


error_reporting(E_ALL);

$bq = new BigQueryClient([
    'projectId' => 'les-bienfaiteurs',
    'keyFilePath' => __DIR__ . '/credentials.json'
]);

if(isset($_GET['postal']) && isset($_GET['order']))
{
	$postal = (int)$_GET['postal'];
	$order_number = (int)$_GET['order'];

	if (!filter_var($postal, FILTER_VALIDATE_INT)) 
	{
	 	die("Ce code postal est incorrect");
	}
	else 
	{
		if (!filter_var($order_number, FILTER_VALIDATE_INT)) 
		{
			die("Ce numéro de commande est incorrect");
		}
		else 
		{
			$order = array();
			foreach($bq->runQuery($bq->query('SELECT * FROM `les-bienfaiteurs.lesbienfaiteurs.view_clientsFulfillment` WHERE shippingZip="'.$postal.'" AND order_number='.$order_number)) as $row)
			{
				$order["order_number"] = $row["order_number"]; 
				if(!isset($order["fulfillments"][($row["fulfillmentTrackingNumber"] ?? "OS ".$row["brand"])]))
				{
					switch ($row["fulfillmentCompany"]) {
						case 'Colissimo':
						case 'colissimo-access':
							$row["fulfillmentCompany"] = "Colissimo";
							$order["fulfillments"][($row["fulfillmentTrackingNumber"] ?? "OS ".$row["brand"])]["fulfillmentTrackingURL"] = "https://www.laposte.fr/outils/suivre-vos-envois?code=".$row["fulfillmentTrackingNumber"];
							break;
						case 'La Poste':
						case 'Lettre Suivie':
							$row["fulfillmentCompany"] = "La Poste";
							$order["fulfillments"][($row["fulfillmentTrackingNumber"] ?? "OS ".$row["brand"])]["fulfillmentTrackingURL"] = "https://www.laposte.fr/outils/suivre-vos-envois?code=".$row["fulfillmentTrackingNumber"];
							break;
						case 'Colis Privé':
						case 'colisprive-tracked':
							$row["fulfillmentCompany"] = "Colis Privé";
							$order["fulfillments"][($row["fulfillmentTrackingNumber"] ?? "OS ".$row["brand"])]["fulfillmentTrackingURL"] = "https://colisprive.com/moncolis/pages/detailColis.aspx?numColis=".$row["fulfillmentTrackingNumber"].$row["shippingZip"]."&lang=FR";
							break;
						case 'Mondial Relay':
						case 'mondialrelay-relaisl':
							$row["fulfillmentCompany"] = "Mondial Relay";
							$order["fulfillments"][($row["fulfillmentTrackingNumber"] ?? "OS ".$row["brand"])]["fulfillmentTrackingURL"] = "https://www.mondialrelay.fr/suivi-de-colis?brand=Les+Bienfaiteurs&codePostal=".$row["shippingZip"]."&numeroExpedition=".$row["fulfillmentTrackingNumber"];
							break;
						case 'GLS':
						case 'Chronopost FR 18h':
						case 'DHL Express':
						default:
							$order["fulfillments"][($row["fulfillmentTrackingNumber"] ?? "OS ".$row["brand"])]["fulfillmentTrackingURL"] = Null;
							break;
					}
					foreach(["order_number","fulfillmentCompany","fulfillmentTrackingNumber","fulfillmentCreationDate","fulfillmentPreparedDate","fulfillmentAttemptedDate","fulfillmentDeliveredDate","expectedDeliveryDate", "adjustedExpectedDeliveryDate", "fulfillmentStatus", "globalStatus"] as $field)
					{
						$order["fulfillments"][($row["fulfillmentTrackingNumber"] ?? "OS ".$row["brand"])][$field] = $row[$field];
					}
				}

				$orderline = array();
				foreach(["quantity","label","brand","image"] as $field)
				{
					$orderline[$field] = $row[$field];
				}
				$order["fulfillments"][($row["fulfillmentTrackingNumber"] ?? "OS ".$row["brand"])]["lines"][] = $orderline;
			}

			if(isset($order["fulfillments"]))
			{
				foreach($order["fulfillments"] as $fulfillmentId => $fulfillment)
				{
					$order["fulfillments"][$fulfillmentId]["title"] = implode("/", array_unique(array_column($fulfillment["lines"], "brand")));
				}
				$order["fulfillments"] = array_values($order["fulfillments"]);
			}
		}
	}
}
include "templates/delivery.php";