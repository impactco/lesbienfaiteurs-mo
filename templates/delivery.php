<!DOCTYPE html>
<html lang="fr">
  <head>
      <meta charset="UTF-8">
      <?php /* <meta name="viewport" content="=devicscale=1.0"> */ ?>
      <title>Votre commande<?=(isset($order["order_number"]) ? " #".$order["order_number"] : "")?></title>
      
      <!-- Intégration de Bootstrap CSS (à partir du CDN) -->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

      <!-- Chargement de Lato -->
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
      <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@1,700&display=swap">

      <!-- Styles personnalisés -->
      <style>
          body {
              font-family: 'Lato', sans-serif;
              background-color: #f6f3ef;
              color: #3c3937;
          }

          h1, h2, h3, h4, h5, h6 {
              color: #da8058;
          }

          .table-primary {
              --bs-table-color: #fff;
              --bs-table-bg: #da8058;
          }

          @media (max- {
            body {
              font-size: .875em; /* Choisissez la taille de police souhaitée pour les mobiles */
            }
          }
      </style>

      <!-- Intégration de Bootstrap JS (à partir du CDN) -->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

      <!-- Intégration de jQuery (à partir du CDN) -->
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  </head>
  <body class="m-0">
    <?php 
    if(isset($order))
    {
      if(isset($order["fulfillments"]))
      {
        ?>
        <div class="bg-white mb-5">
          <h1 class="pt-5 pb-2 text-center"><i>Votre commande #<?=$order["order_number"]?></i></h1>
          <?php 
          if(count($order["fulfillments"]) > 1)
          {
            ?>
            <h4 class="text-dark text-center">Votre commande sera livrée en <?=count($order["fulfillments"])?> colis</h4>
            <?php
          }
          ?>
          <br>
        </div>
        <?php /*
        <div class="container-lg padding-bottom-3x mb-3">
          <div class="alert text-center">
            <h4>👀 Incident de livraison sur votre commande Evila</h4>
            <p>Une invasion de sauterelles s'est produite à l'entrepôt. Nous mettons tout en œuvre pour expédier votre commande avant le 28 décembre.</p>
          </div>
        </div>
        */ ?>
        <?php
        foreach($order["fulfillments"] as $key => $fulfillment)
        {
          ?>
          <div class="container-lg padding-bottom-3x mb-1">
            <div class="card mb-5 border-0">
              <?php 
              if(count($order["fulfillments"]) > 1)
              {
                ?>
                <span class="p-3  text-lg rounded-top" ><h5 class="text-uppercase text-center text-dark m-0"><?=$fulfillment["title"]?></h5></span>
                <?php
              }
              ?>
              <div id="fulfillCollapse<?=$key?>" class="collapse show">
                <div class="d-flex flex-wrap flex-sm-nowrap justify-content-between py-3 px-2 mb-3" style="background-color: #ECE5DF;">
                  <?php 
                  if($fulfillment["fulfillmentCompany"])
                  {
                    ?>
                    <div class="w-100 text-center py-1 px-2"><span class="text-medium">Expédié par : </span>
                      <?php 
                      if($fulfillment["fulfillmentTrackingURL"])
                      {
                        ?>
                        <a href="<?=$fulfillment["fulfillmentTrackingURL"]?>" target="_blank"><?=$fulfillment["fulfillmentCompany"]?></a>
                        <?php
                      }
                      else
                      {
                        ?>
                        <?=$fulfillment["fulfillmentCompany"]?>
                        <?php
                      }
                      ?>
                    </div>
                    <?php
                  }
                  ?>
                  <div class="w-100 text-center py-1 px-2"><span class="text-medium">Statut : </span><?=$fulfillment["fulfillmentStatus"]?></div>
                  <div class="w-100 text-center py-1 px-2">
                    <?php
                    if(!isset($fulfillment["fulfillmentDeliveredDate"]))
                    {
                      ?>
                      <span class="text-medium">Date de livraison estimée : </span><?=date("d/m/Y", strtotime($fulfillment["expectedDeliveryDate"]))?><?=date("d/m/Y", strtotime($fulfillment["adjustedExpectedDeliveryDate"]))?></span>
                      <?php
                    }
                    else
                    {
                      ?>
                      <span class="text-medium">Date de livraison : </span><?=date("d/m/Y", strtotime($fulfillment["fulfillmentDeliveredDate"]))?></span>
                      <?php
                    }
                    ?>
                  </div>         
                </div>
                <div class="card-body">
                  <div class="steps d-flex flex-wrap flex-sm-nowrap justify-content-between padding-top-2x padding-bottom-1x">
                    <div class="step step1 completed">
                      <div class="step-icon-wrap">
                        <div class="step-icon"></div>
                      </div>
                      <h4 class="step-title" style="line-height:1.4">Votre commande est<br>enregistrée</h4>
                    </div>
                    <div class="step step2 <?=(isset($fulfillment["fulfillmentTrackingNumber"]) ? 'completed' : '')?>">
                      <div class="step-icon-wrap">
                        <div class="step-icon"></div>
                      </div>
                      <h4 class="step-title" style="line-height:1.4">Votre commande est<br>préparée</h4>
                    </div>
                    <div class="step step3 <?=(isset($fulfillment["fulfillmentTrackingNumber"]) ? 'completed' : '')?>">
                      <div class="step-icon-wrap">
                        <div class="step-icon"></div>
                      </div>
                      <h4 class="step-title" style="line-height:1.4">Votre commande est<br>expédiée</h4>
                    </div>
                    <div class="step step4 <?=(isset($fulfillment["fulfillmentDeliveredDate"]) ? 'completed' : '')?>">
                      <div class="step-icon-wrap">
                        <div class="step-icon"></div>
                      </div>
                      <h4 class="step-title" style="line-height:1.4">Votre commande est<br>livrée</h4>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="d-flex justify-content-center">
                      <div class="w-25"></div>
                      <div class="w-50">
                        <?php
                        foreach($fulfillment["lines"] as $orderline)
                        {
                          ?>
                          <div class="d-flex flex-wrap flex-sm-nowrap justify-content-between padding-top-2x padding-bottom-1x">
                            <div class="p-2"><img width="50" class="border" src="<?=($orderline["image"] ? $orderline["image"] : 'data:image/gif;base64,R0lGODlhAQABAIAAAMLCwgAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==')?>"></div>
                            <div class="p-2 align-self-center"> <?=$orderline["quantity"]?> </div>
                            <div class="p-2 align-self-center w-100"><?=$orderline["label"]?></div>
                          </div>
                          <?php
                        }
                        ?>
                        <br>
                        <?php 
                        if($fulfillment["fulfillmentTrackingURL"])
                        {
                          ?>
                          <div class="d-grid gap-2 col-6 mx-auto pb-3">
                            <a class="btn btn-primary rounded" href="<?=$fulfillment["fulfillmentTrackingURL"]?>" target="_blank">Voir les détails du transporteur</a>
                          </div>
                          <?php
                        }
                        ?>
                      </div>
                      <div class="w-25"></div>
                  </div>
                </div>
              </div>
              <?php 
                if(count($order["fulfillments"]) > 1)
                {
                  ?>
                  </div>
                <?php
              }
              ?>
            </div>
          </div>
        <?php
        }
      }
      else
      {
        ?>
        <div class="bg-white p-5 mb-5">
          <h4 class="text-dark text-center">Nous n'avons pas pu retrouver votre commande</h4>
        </div>
        <?php
      }
      ?>
      <div class="d-grid gap-2 col-2 mx-auto pb-3">
        <a class="btn btn-secondary rounded" href="mailto:bonjour@lesbienfaiteurs.com?subject=Ma+commande<?=(isset($order["order_number"]) ? "+%23".$order["order_number"] : "")?>" target="_blank">Contacter le service client</a>
      </div>
      <style>
      body{
        margin-top:20px;
      }

      .steps .step {
          display: block;
          width: 100%;
          margin-bottom: 15px;
          text-align: center
      }

      .steps .step .step-icon-wrap {
          display: block;
          position: relative;
          width: 100%;
          height: 80px;
          text-align: center
      }

      .steps .step .step-icon-wrap::before,
      .steps .step .step-icon-wrap::after {
          display: block;
          position: absolute;
          top: 50%;
          width: 50%;
          height: 3px;
          margin-top: -1px;
          background-color: #ECE5DF;
          content: '';
          z-index: 1
      }

      .steps .step .step-icon-wrap::before {
          left: 0
      }

      .steps .step .step-icon-wrap::after {
          right: 0
      }

      .steps .step .step-icon {
          display: inline-block;
          position: relative;
          width: 80px;
          height: 80px;
          border-radius: 50%;
          background-color: #ECE5DF;
          color: #374250;
          font-size: 38px;
          line-height: 81px;
          z-index: 5
      }

      .steps .step .step-title {
          margin-top: 16px;
          margin-bottom: 0;
          color: #606975;
          font-size: 14px;
          font-weight: 500
      }

      .steps .step:first-child .step-icon-wrap::before {
          display: none
      }

      .steps .step:last-child .step-icon-wrap::after {
          display: none
      }

      .steps .step.completed .step-icon-wrap::before,
      .steps .step.completed .step-icon-wrap::after {
          background-color: #da8058
      }

      .steps .step.completed .step-icon {
          border-color: #da8058;
          background-color: #da8058;
          color: #fff
      }

      .steps .step.step1 .step-icon {
        background: url('https://cdn.shopify.com/s/files/1/0505/8648/4918/files/1-enregistre-beige-80x80.png?v=1701144488');
      }

      .steps .step.step1.completed .step-icon {
        background: url('https://cdn.shopify.com/s/files/1/0505/8648/4918/files/1-enregistre-orange-80x80.png?v=1701144488');
      }

      .steps .step.step2 .step-icon {
        background: url('https://cdn.shopify.com/s/files/1/0505/8648/4918/files/2-preparee-beige-80x80.png?v=1701144488');
      }

      .steps .step.step2.completed .step-icon {
        background: url('https://cdn.shopify.com/s/files/1/0505/8648/4918/files/2-preparee-orange-80x80.png?v=1701144488');
      }

      .steps .step.step3 .step-icon {
        background: url('https://cdn.shopify.com/s/files/1/0505/8648/4918/files/3-expedition-beige-80x80.png?v=1701144488');
      }

      .steps .step.step3.completed .step-icon {
        background: url('https://cdn.shopify.com/s/files/1/0505/8648/4918/files/3-expedition-orange-80x80.png?v=1701144488');
      }

      .steps .step.step4 .step-icon {
        background: url('https://cdn.shopify.com/s/files/1/0505/8648/4918/files/4-livree-beige-80x80.png?v=1701144488');
      }

      .steps .step.step4.completed .step-icon {
        background: url('https://cdn.shopify.com/s/files/1/0505/8648/4918/files/4-livree-orange-80x80.png?v=1701144488');
      }


      @media (max-width: 576px) {
          .flex-sm-nowrap .step .step-icon-wrap::before,
          .flex-sm-nowrap .step .step-icon-wrap::after {
              display: none
          }
      }

      @media (max-width: 768px) {
          .flex-md-nowrap .step .step-icon-wrap::before,
          .flex-md-nowrap .step .step-icon-wrap::after {
              display: none
          }
      }

      @media (max-width: 991px) {
          .flex-lg-nowrap .step .step-icon-wrap::before,
          .flex-lg-nowrap .step .step-icon-wrap::after {
              display: none
          }
      }

      @media (max-width: 1200px) {
          .flex-xl-nowrap .step .step-icon-wrap::before,
          .flex-xl-nowrap .step .step-icon-wrap::after {
              display: none
          }
      }

      .bg-faded, .bg-secondary {
          background-color: #ECE5DF !important;
      }
      .btn-primary {
          border-radius: 0;
          border-color: #3c3937 !important; 
          background-color: #3c3937 !important; 
      }

      .btn-secondary {
          border-radius: 0;
          border-color: #da8058 !important; 
          background-color: #da8058 !important; 
      }
      </style>
      <?php
    }
    else
    {
      ?>
      <div data-tf-live="01HKPYQD050K6AGEMPZ127MB7B"></div><script src="//embed.typeform.com/next/embed.js"></script>
      <?php
    }
    ?>
  </body>
</html>