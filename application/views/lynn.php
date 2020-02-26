<!DOCTYPE html>
  <html>
    <head>
     
      <!--Import materialize.css-->
      <link type="text/css" rel="stylesheet" href=" <?php echo(base_url()); ?>/assets/css/materialize.min.css"  media="screen,projection"/>
      <link type="text/css" rel="stylesheet" href="<?php echo(base_url()); ?>/assets/css/lynn.css?ekkedk"  media="screen,projection"/>

      <!--Let browser know website is optimized for mobile-->
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>

    <body>

      <div class="row valign-wrapper">
        <div class="col s2 " style="padding-right: 0px;padding-left: 0px;">
          <center>
            <img src="<?php echo(base_url()); ?>/assets/img/lynn.png" class="responsive-img">
          </center>
          
        </div>
        <div class="col s10">
          <nav>
            <div class="nav-wrapper">
              <form>
                <div class="input-field">
                  <input id="search" type="search" required>
                  <label class="label-icon" for="search"><i class="material-icons">search</i></label>
                  <i class="material-icons">close</i>
                </div>
              </form>
            </div>
          </nav>
        </div>
      </div>



      <center>
        <div class="row big_lister">
          <div class="col s3">
            <span class="badge1" data-badge="+12">
              <a class="btn-floating btn-large waves-effect waves-light blue commandes"><i class="material-icons left">shopping_cart</i></a>
            </span>
          </div>
          <div class="col s3"><a class="btn-floating btn-large waves-effect waves-light blue livraisons"><i class="material-icons left">attach_money</i></a></div>
          <div class="col s3"><a class="btn-floating btn-large waves-effect waves-light modal-trigger red ajouter" data-target="newProduct"><i class="material-icons left">add</i></a></div>
          <div class="col s3"><a class="btn-floating btn-large waves-effect waves-light pulse listProduit"><i class=" material-icons left">format_list_bulleted</i></a></div>
        </div>
      </center>

      <center style="display: none;" class="lister">
        <div class="row my_list">
          <div class="col s6 m3 l3"><a class="waves-effect waves-light btn blue accessoires">Accessoires</a></div>
          <div class="col s6 m3 l3"><a class="waves-effect waves-light btn blue cheveux">Cheveux</a></div>
          <div class="col s6 m3 l3"><a class="waves-effect waves-light btn blue cosmetique">Cosmetique</a></div>
          <div class="col s6 m3 l3"><a class="waves-effect waves-light btn blue vetements">Vêtements</a></div>
        </div>
      </center>
     


      <div class="displayer row">
        <div class="col s12 m4 l3" style="display: none;">
          <div class="card">
            <div class="card-image waves-effect waves-block waves-light">
              <img class="activator" src="<?php echo(base_url()); ?>/assets/img/sample-1.jpg">
            </div>
            <div class="card-content">
              <span class="card-title activator grey-text text-darken-4">Card Title<i class="material-icons right">more_vert</i></span>
              <p class="red-text text-darken-2"><span class="price">12000 FCFA</span> <s><span class="promo">28000 FCFA</span></s> </p>
             
              <p> <span class="blue-text text-darken-2 categorie_text">Vêtements</span> <br> Commandes:18 <br>
              Ventes:18 
               </p>
              <p>
                <form action="#">
                  <p class="disponibility">
                    <label>
                      <input name="group1" type="radio" value="oui" checked />
                      <span>Disponible</span>
                    </label>

                     <label>
                      <input name="group1" type="radio" value="non" />
                      <span>Indisponible</span>
                    </label>
                  </p>
                </form>
              </p>
            </div>
            <div class="card-reveal">
              <span class="card-title grey-text text-darken-4">Card Title<i class="material-icons right">close</i></span>
                <div class="row">
                  <form class="col s12">
                    <div class="row">
                      <div class="input-field col s12">
                        <input placeholder="le nom" id="name" type="text" class="validate">
                        <label for="name">Nom du produit</label>
                      </div>
                      <div class="input-field col s12">
                        <input id="prix" type="text" class="validate">
                        <label for="prix">Prix</label>
                      </div>

                      <div class="input-field col s12">
                        <input id="promotion" type="text" class="validate">
                        <label for="promotion">Prix Promotionnel</label>
                      </div>

                      <center>
                        <div class="row">
                          <div class="col s4">
                            <a class="btn-floating btn-large waves-effect waves-light blue"><i class="material-icons left">photo</i></a>
                          </div>
                          
                          <div class="col s4">
                            <a class="btn-floating btn-large waves-effect waves-light blue"><i class="material-icons">save</i></a>
                          </div>

                          <div class="col s4">
                            <a class="btn-floating btn-large waves-effect waves-light red"><i class="material-icons">delete_forever</i></a>
                          </div>
                        </div>
                      </center>
                    </div>
                  </form>
                </div>
            </div>
          </div>
        </div>
      </div>



      <div id="newProduct" class="modal">
        <div class="modal-content">
          <h5>Ajouter un nouveau produit</h5>

          <div class="row">
            <form class="col s12">
              <div class="row">
                <div class="input-field col s12">
                  <input placeholder="le nom" id="Productname" class="initiate" type="text">
                  <label for="Productname">Nom du produit</label>
                </div>
                <div class="input-field col s12">
                  <input id="Productprix" type="text" class="initiate" >
                  <label for="Productprix">Prix</label>
                </div>

                <div class="input-field col s12">
                  <input id="Productpromotion" type="text" class="initiate">
                  <label for="Productpromotion">Prix Promotionnel</label>
                </div>


                <div class="input-field col s12" class="initiate">
                  <select id="Productcategorie">
                    <optgroup label="Choisi">
                      <option value="No Category">Sectionnner une catégorie</option>
                    </optgroup>
                    <optgroup label="Accessoires">
                      <option value="Bijoux">Bijoux</option>
                      <option value="Ceintures">Ceintures</option>
                      <option value="Chapeaux femmes">Chapeaux femmes</option>
                      <option value="Foulards">Foulards</option>
                      <option value="Montres">Montres</option>
                      <option value="Sacs et pochettes">Sacs et pochettes</option>
                    </optgroup>
                    <optgroup label=""Cheveux">
                      <option value="Greffes">Greffes</option>
                      <option value="Mêches et tissages">Mêches et tissages</option>
                      <option value="Sèches cheveux et lisseurs">Sèches cheveux et lisseurs</option>
                    </optgroup>
                    <optgroup label="cosmetique">
                      <option value="Déodorants">Déodorants</option>
                      <option value="Maquillages">Maquillages</option>
                      <option value="Soins du corps et épilation">Soins du corps et épilation</option>
                      <option value="Soins du visage">Soins du visage</option>
                      <option value="Soins pour cheveux">Soins pour cheveux</option>
                    </optgroup>
                    <optgroup label="Vêtements">
                      <option value="Afritudes">Afritudes</option>
                      <option value="Chaussettes et collants">Chaussettes et collants</option>
                      <option value="Chaussures">Chaussures</option>
                      <option value="Chemises">Chemises</option>
                      <option value="Jupes">Jupes</option>
                      <option value="Lingerie">Lingerie</option>
                      <option value="Robes">Robes</option>
                      <option value="Tops et t-shirts">Tops et t-shirts</option>
                      <option value="Vestes et manteaux">Vestes et manteaux</option>
                    </optgroup>
                  </select>
                  <label for="Productcategorie">Categorie</label>
                </div>

                <div class="progress addNewImage" style="display: none;">
                  <div class="determinate" style="width: 0%"></div>
                </div>

                <div class="warning_message" style="display: none;">
                  <span class="red-text text-darken-2"></span>
                </div>
            


                <center>
                  <div class="row">
                    <div class="col s4">
                      <a class="btn-floating btn-large waves-effect waves-light blue addPic"><i class="material-icons left">photo</i></a>
                    </div>
                    
                    <div class="col s4">
                      <a class="btn-floating btn-large waves-effect waves-light blue record"><i class="material-icons">save</i></a>
                    </div>

                     <div class="col s4">
                      <a class="btn-floating btn-large waves-effect waves-light red modal-close"><i class="material-icons">close</i></a>
                    </div>
                  </div>
                </center>
              </div>
            </form>
          </div>
        </div>
      </div>


      <input style="display: none;" type="file" name="ImageProduct" id="ImageProduct" />


      <div class="hider" base_url="<?php echo(base_url()) ?>" get_it="/welcome/get_it" json_categories="<?php echo(base_url()); ?>/assets/categories.json" site_url="<?php echo(site_url()) ?>"  url_add_new_product="/welcome/do_upload" style="display: none;"></div>
      

      <!--JavaScript at end of body for optimized loading-->
      <script type="text/javascript" src="<?php echo(base_url()); ?>/assets/js/jquery.js"></script>
      <script type="text/javascript" src="<?php echo(base_url()); ?>/assets/js/materialize.min.js"></script>
      <script type="text/javascript" src="<?php echo(base_url()); ?>/assets/js/lynn.js?ekkekd"></script>
    </body>

     

  </html>